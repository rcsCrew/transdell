<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/VisionAnalyzer.php';
require_once __DIR__ . '/Mailer.php';

const MAX_TENTATIVAS_FOTO = 5;

/**
 * Lógica de processar 1 foto (chamar a IA e gravar o resultado) e de fechar
 * um checklist quando todos os itens terminam. Compartilhado entre o worker
 * agendado (worker/processar_fotos.php) e o disparo imediato depois do
 * upload (api/processar_foto_agora.php) — os dois fazem exatamente a mesma
 * coisa, só mudam quando são chamados.
 */

function log_processamento(string $msg): void
{
    $linha = '[' . date('Y-m-d H:i:s') . '] ' . $msg;
    if (PHP_SAPI === 'cli') {
        echo $linha . PHP_EOL;
    }
    error_log($linha);
}

/**
 * Contenção anti-loop / anti-custo-descontrolado: soma 1 no contador do dia
 * e diz se ainda está dentro do LIMITE_IA_DIARIO. Se estourar, quem chamou
 * deve adiar a análise (sem gastar "tentativa" da foto) — no dia seguinte o
 * contador zera sozinho e o processamento volta ao normal automaticamente.
 */
function dentro_do_limite_diario(): bool
{
    $hoje = date('Y-m-d');
    $stmt = db()->prepare('
        INSERT INTO limite_ia_diario (dia, chamadas) VALUES (?, 1)
        ON DUPLICATE KEY UPDATE chamadas = chamadas + 1
    ');
    $stmt->execute([$hoje]);

    $stmt = db()->prepare('SELECT chamadas FROM limite_ia_diario WHERE dia = ?');
    $stmt->execute([$hoje]);
    $chamadas = (int)$stmt->fetchColumn();

    return $chamadas <= LIMITE_IA_DIARIO;
}

function processar_foto_por_id(int $fotoId): void
{
    $stmt = db()->prepare('
        SELECT f.id, f.foto_path, f.tentativas, f.status, f.precisa_revisao_manual, f.checklist_item_id,
               i.item_nome, i.criterios_analise
        FROM checklist_fotos f
        JOIN checklist_itens i ON i.id = f.checklist_item_id
        WHERE f.id = ?
    ');
    $stmt->execute([$fotoId]);
    $foto = $stmt->fetch();

    // Já processada, não existe, travada esperando revisão manual, ou
    // esgotou tentativas — nada a fazer (evita reprocessar por engano se o
    // disparo imediato e o worker agendado se cruzarem).
    if (!$foto || $foto['status'] !== null || $foto['precisa_revisao_manual'] || (int)$foto['tentativas'] >= MAX_TENTATIVAS_FOTO) {
        return;
    }

    $caminhoAbsoluto = UPLOAD_DIR . '/' . $foto['foto_path'];

    if (!is_file($caminhoAbsoluto)) {
        log_processamento("Foto #{$foto['id']} não existe mais no disco ({$foto['foto_path']}), marcando pra revisão manual.");
        $stmt = db()->prepare('UPDATE checklist_fotos SET precisa_revisao_manual = 1, erro_ia = ? WHERE id = ?');
        $stmt->execute(['Arquivo da foto não encontrado no servidor.', $foto['id']]);
        atualizar_status_agregado_item((int)$foto['checklist_item_id']);
        return;
    }

    if (!dentro_do_limite_diario()) {
        log_processamento("Foto #{$foto['id']} adiada — limite diário de " . LIMITE_IA_DIARIO . ' chamadas de IA atingido. Volta ao normal amanhã.');
        return; // não gasta tentativa, tenta de novo depois
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($caminhoAbsoluto);

    try {
        $resultado = VisionAnalyzer::analisarFoto($caminhoAbsoluto, $mime, $foto['item_nome'], $foto['criterios_analise']);

        $stmt = db()->prepare('
            UPDATE checklist_fotos
            SET status = ?, observacao_ia = ?, classificacao = ?, vida_util_percentual = ?,
                tentativas = tentativas + 1, erro_ia = NULL
            WHERE id = ?
        ');
        $stmt->execute([
            $resultado['status'], $resultado['observacao'],
            $resultado['classificacao'], $resultado['vida_util_percentual'],
            $foto['id'],
        ]);

        $detalhe = $resultado['classificacao']
            ? "{$resultado['classificacao']} (~{$resultado['vida_util_percentual']}% vida útil)"
            : $resultado['status'];
        log_processamento("Foto #{$foto['id']} ({$foto['item_nome']}) -> {$detalhe}");
    } catch (VisionAnalysisException $e) {
        $novasTentativas = (int)$foto['tentativas'] + 1;
        $precisaRevisao = $novasTentativas >= MAX_TENTATIVAS_FOTO ? 1 : 0;

        $stmt = db()->prepare('
            UPDATE checklist_fotos
            SET tentativas = ?, precisa_revisao_manual = ?, erro_ia = ?
            WHERE id = ?
        ');
        $stmt->execute([$novasTentativas, $precisaRevisao, $e->getMessage(), $foto['id']]);

        $msg = "Foto #{$foto['id']} falhou (tentativa {$novasTentativas}/" . MAX_TENTATIVAS_FOTO . "): " . $e->getMessage();
        log_processamento($precisaRevisao ? $msg . ' — esgotou tentativas, precisa de revisão manual do gestor.' : $msg);
    }

    atualizar_status_agregado_item((int)$foto['checklist_item_id']);

    $stmt = db()->prepare('SELECT checklist_id FROM checklist_itens WHERE id = ?');
    $stmt->execute([$foto['checklist_item_id']]);
    $checklistId = $stmt->fetchColumn();
    if ($checklistId) {
        tentar_finalizar_checklist((int)$checklistId);
    }
}

/** Fecha o checklist (calcula totais + dispara alerta) se todos os itens já tiverem status. */
function tentar_finalizar_checklist(int $checklistId): void
{
    $stmt = db()->prepare('
        SELECT c.id, c.placa, m.nome AS motorista_nome
        FROM checklists c
        JOIN motoristas m ON m.id = c.motorista_id
        WHERE c.id = ? AND c.status = "enviado" AND c.analise_concluida = 0
    ');
    $stmt->execute([$checklistId]);
    $checklist = $stmt->fetch();
    if (!$checklist) {
        return; // ainda é rascunho, já foi concluído antes, ou não existe
    }

    $stmt = db()->prepare('SELECT status FROM checklist_itens WHERE checklist_id = ?');
    $stmt->execute([$checklistId]);
    $statusItens = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($statusItens) || in_array(null, $statusItens, true)) {
        return; // ainda tem item sem terminar de classificar
    }

    $totais = ['ok' => 0, 'atencao' => 0, 'critico' => 0];
    foreach ($statusItens as $s) {
        $totais[$s] = ($totais[$s] ?? 0) + 1;
    }

    $stmt = db()->prepare('
        UPDATE checklists
        SET analise_concluida = 1, total_ok = ?, total_atencao = ?, total_critico = ?
        WHERE id = ?
    ');
    $stmt->execute([$totais['ok'], $totais['atencao'], $totais['critico'], $checklistId]);

    log_processamento("Checklist #{$checklistId} (placa {$checklist['placa']}) terminou de ser analisado: {$totais['ok']} ok, {$totais['atencao']} atenção, {$totais['critico']} crítico.");

    if ($totais['critico'] > 0) {
        enviar_alerta_se_necessario($checklistId, $checklist);
    }
}

function enviar_alerta_se_necessario(int $checklistId, array $checklistInfo): void
{
    $stmt = db()->prepare('SELECT id FROM alertas_enviados WHERE checklist_id = ? AND canal = "email"');
    $stmt->execute([$checklistId]);
    if ($stmt->fetch()) {
        return; // já alertou esse checklist antes
    }

    $stmt = db()->prepare('
        SELECT i.item_nome, f.observacao_ia, f.classificacao, f.vida_util_percentual
        FROM checklist_itens i
        JOIN checklist_fotos f ON f.checklist_item_id = i.id
        WHERE i.checklist_id = ? AND i.status = "critico" AND f.status = "critico"
    ');
    $stmt->execute([$checklistId]);
    $itensCriticos = $stmt->fetchAll();

    [$sucesso, $erro] = Mailer::enviarAlertaCritico($checklistInfo, $itensCriticos);

    $stmt = db()->prepare('INSERT INTO alertas_enviados (checklist_id, canal, destino, sucesso, erro) VALUES (?, "email", ?, ?, ?)');
    $stmt->execute([$checklistId, MAIL_TO, $sucesso ? 1 : 0, $erro]);

    $stmt = db()->prepare('INSERT INTO alertas_enviados (checklist_id, canal, destino, sucesso) VALUES (?, "painel", NULL, 1)');
    $stmt->execute([$checklistId]);

    log_processamento("Alerta de crítico enviado pro checklist #{$checklistId} (sucesso=" . ($sucesso ? 'sim' : 'não') . ')');
}
