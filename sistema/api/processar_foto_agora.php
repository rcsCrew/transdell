<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/ChecklistProcessamento.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder_json(['erro' => 'Método não permitido'], 405);
}

$sessaoChecklist = $_SESSION['checklist_atual'] ?? null;
$checklistId = (int)($_POST['checklist_id'] ?? 0);
$fotoId = (int)($_POST['foto_id'] ?? 0);

if (!$sessaoChecklist || $sessaoChecklist['id'] !== $checklistId || $fotoId === 0) {
    responder_json(['erro' => 'Requisição inválida.'], 403);
}

// Confirma que a foto pertence mesmo a esse checklist antes de processar
// (evita disparar análise de foto de outro checklist trocando o id).
$stmt = db()->prepare('
    SELECT f.id FROM checklist_fotos f
    JOIN checklist_itens i ON i.id = f.checklist_item_id
    WHERE f.id = ? AND i.checklist_id = ?
');
$stmt->execute([$fotoId, $checklistId]);
if (!$stmt->fetch()) {
    responder_json(['erro' => 'Foto não encontrada nesse checklist.'], 404);
}

// Dá tempo pra cadeia de provedores de IA terminar (até 2 tentativas de
// 45s cada) mesmo que o host tenha um limite padrão mais baixo. Se o host
// matar o processo antes disso mesmo assim, sem problema — o worker
// agendado (a cada 1 minuto) processa essa foto na próxima passada.
set_time_limit(90);

try {
    processar_foto_por_id($fotoId);
} catch (Throwable $e) {
    error_log('Erro no processamento imediato da foto #' . $fotoId . ': ' . $e->getMessage());
}

responder_json(['ok' => true]);
