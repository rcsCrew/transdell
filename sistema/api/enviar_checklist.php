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
    responder_json(['erro' => 'Checklist inválido ou sessão expirada.'], 403);
}

try {
    $totalItensAtivos = (int)db()->query('SELECT COUNT(*) c FROM itens_padrao WHERE ativo = 1')->fetch()['c'];

    // Só exige que cada item tenha pelo menos 1 foto. A classificação da IA
    // roda depois, em segundo plano — não trava o motorista aqui.
    $stmt = db()->prepare('
        SELECT i.id
        FROM checklist_itens i
        JOIN checklist_fotos f ON f.checklist_item_id = i.id
        WHERE i.checklist_id = ?
        GROUP BY i.id
    ');
    $stmt->execute([$checklistId]);
    $itensComFoto = $stmt->fetchAll();

    if (count($itensComFoto) < $totalItensAtivos) {
        responder_json(['erro' => 'Faltam fotos de algum item do checklist.'], 400);
    }

    $stmt = db()->prepare('UPDATE checklists SET status = "enviado", enviado_em = NOW() WHERE id = ?');
    $stmt->execute([$checklistId]);

    unset($_SESSION['checklist_atual']);

    responder_json(['ok' => true]);
} catch (Throwable $e) {
    error_log('Erro inesperado em enviar_checklist.php: ' . $e->getMessage());
    responder_json(['erro' => 'Erro inesperado no servidor ao enviar o checklist. Tente de novo.'], 500);
}
