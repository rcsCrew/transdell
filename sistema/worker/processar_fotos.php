<?php
/**
 * Worker de análise das fotos do checklist — a rede de segurança. Roda
 * sozinho, chamado por uma tarefa agendada (cron) a cada 1 minuto. A
 * maioria das fotos já é processada na hora (api/processar_foto_agora.php,
 * disparado pelo navegador do motorista logo após o upload) — esse worker
 * só existe pra pegar o que sobrar: fotos cujo disparo imediato falhou
 * (rede caiu, timeout, motorista fechou o app antes de terminar) e
 * checklists que ficaram travados sem fechar por algum motivo.
 *
 * Uso: php worker/processar_fotos.php
 */
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/ChecklistProcessamento.php';

const LOTE_POR_EXECUCAO = 8;

log_processamento('--- Worker iniciado ---');

$stmt = db()->prepare('
    SELECT f.id
    FROM checklist_fotos f
    WHERE f.status IS NULL AND f.precisa_revisao_manual = 0 AND f.tentativas < ?
    ORDER BY f.criado_em ASC
    LIMIT ' . LOTE_POR_EXECUCAO . '
');
$stmt->execute([MAX_TENTATIVAS_FOTO]);
$fotoIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($fotoIds as $fotoId) {
    processar_foto_por_id((int)$fotoId);
}
log_processamento('Fotos processadas nesse lote: ' . count($fotoIds));

// Segunda passada: fecha qualquer checklist enviado que ainda não concluiu
// (cobre o caso raro do disparo imediato ter processado a última foto mas
// não ter conseguido fechar o checklist por algum motivo).
$stmt = db()->query('SELECT id FROM checklists WHERE status = "enviado" AND analise_concluida = 0');
foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $checklistId) {
    tentar_finalizar_checklist((int)$checklistId);
}

log_processamento('--- Worker terminou ---');
