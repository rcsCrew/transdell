<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder_json(['erro' => 'Método não permitido'], 405);
}

$sessaoChecklist = $_SESSION['checklist_atual'] ?? null;
$checklistId = (int)($_POST['checklist_id'] ?? 0);

if (!$sessaoChecklist || $sessaoChecklist['id'] !== $checklistId) {
    responder_json(['erro' => 'Checklist inválido ou sessão expirada. Reinicie o checklist.'], 403);
}

$itemChave = trim($_POST['item_chave'] ?? '');
$itemNome = trim($_POST['item_nome'] ?? '');
$lat = isset($_POST['lat']) && $_POST['lat'] !== '' ? (float)$_POST['lat'] : null;
$lng = isset($_POST['lng']) && $_POST['lng'] !== '' ? (float)$_POST['lng'] : null;

if ($itemChave === '' || $itemNome === '' || !isset($_FILES['foto'])) {
    responder_json(['erro' => 'Dados incompletos (item ou foto ausente).'], 400);
}

try {
    $stmt = db()->prepare('SELECT id FROM checklists WHERE id = ? AND status = "rascunho"');
    $stmt->execute([$checklistId]);
    if (!$stmt->fetch()) {
        responder_json(['erro' => 'Checklist já foi enviado ou não existe.'], 400);
    }

    // Garante que existe uma linha do item (uma foto pode ser a primeira ou a
    // enésima do mesmo item — o item em si não se duplica). criterios_analise
    // vem sempre do cadastro do item no banco (nunca do cliente), pra IA usar
    // exatamente o que o gestor configurou pra esse item.
    $stmt = db()->prepare('SELECT criterios_analise FROM itens_padrao WHERE chave = ?');
    $stmt->execute([$itemChave]);
    $criterios = $stmt->fetchColumn();
    $criterios = $criterios !== false ? $criterios : null;

    $stmt = db()->prepare('
        INSERT INTO checklist_itens (checklist_id, item_chave, item_nome, criterios_analise)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE item_nome = VALUES(item_nome), criterios_analise = VALUES(criterios_analise)
    ');
    $stmt->execute([$checklistId, $itemChave, $itemNome, $criterios]);

    $stmt = db()->prepare('SELECT id FROM checklist_itens WHERE checklist_id = ? AND item_chave = ?');
    $stmt->execute([$checklistId, $itemChave]);
    $checklistItemId = (int)$stmt->fetchColumn();

    // Contenção anti-loop: trava um número máximo de fotos por item (evita
    // upload sem parar por bug de cliente, dedo no botão ou script malicioso
    // batendo direto no endpoint).
    $stmt = db()->prepare('SELECT COUNT(*) FROM checklist_fotos WHERE checklist_item_id = ?');
    $stmt->execute([$checklistItemId]);
    if ((int)$stmt->fetchColumn() >= MAX_FOTOS_POR_ITEM) {
        responder_json(['erro' => 'Esse item já tem o máximo de ' . MAX_FOTOS_POR_ITEM . ' fotos permitido.'], 400);
    }

    $caminhoRelativo = salvar_foto_upload($_FILES['foto']);

    if ($lat !== null && $lng !== null) {
        $stmt = db()->prepare('UPDATE checklists SET latitude = ?, longitude = ? WHERE id = ?');
        $stmt->execute([$lat, $lng, $checklistId]);
    }

    // Sem chamada de IA aqui — só salva. O worker (worker/processar_fotos.php)
    // analisa em segundo plano, rodando sozinho em intervalos.
    $stmt = db()->prepare('INSERT INTO checklist_fotos (checklist_item_id, foto_path) VALUES (?, ?)');
    $stmt->execute([$checklistItemId, $caminhoRelativo]);
    $fotoId = (int)db()->lastInsertId();

    responder_json([
        'foto_id' => $fotoId,
        'foto_url' => foto_url($caminhoRelativo),
    ]);
} catch (RuntimeException $e) {
    responder_json(['erro' => $e->getMessage()], 400);
} catch (Throwable $e) {
    error_log('Erro inesperado em enviar_foto.php: ' . $e->getMessage());
    responder_json(['erro' => 'Erro inesperado no servidor. Tente tirar a foto de novo.'], 500);
}
