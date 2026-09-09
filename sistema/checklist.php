<?php
declare(strict_types=1);
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$sessaoChecklist = $_SESSION['checklist_atual'] ?? null;
if (!$sessaoChecklist) {
    header('Location: /index.php');
    exit;
}

$stmt = db()->prepare('SELECT chave, nome FROM itens_padrao WHERE ativo = 1 ORDER BY ordem ASC, nome ASC');
$stmt->execute();
$itens = $stmt->fetchAll();

if (empty($itens)) {
    die('Nenhum item de checklist cadastrado. Peça pro gestor cadastrar itens no painel.');
}

$fotosExistentes = [];
$stmt = db()->prepare('
    SELECT i.item_chave, f.id, f.foto_path
    FROM checklist_itens i
    JOIN checklist_fotos f ON f.checklist_item_id = i.id
    WHERE i.checklist_id = ?
    ORDER BY f.id ASC
');
$stmt->execute([$sessaoChecklist['id']]);
foreach ($stmt->fetchAll() as $row) {
    $fotosExistentes[$row['item_chave']][] = [
        'id' => (int)$row['id'],
        'foto_url' => foto_url($row['foto_path']),
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checklist de Frota</title>
<link rel="icon" type="image/png" href="/assets/img/favicon.png">
<link rel="stylesheet" href="/assets/css/style.css?v=2">
</head>
<body>

<header>
  <img class="logo-img" src="/assets/img/transdell-logo.png" alt="Transdell">
  <p class="eyebrow">Saída de viagem</p>
  <h1>Checklist Fotográfico</h1>
  <div class="plate-row">
    <span class="tag"><?= e($sessaoChecklist['placa']) ?></span>
    <span class="tag"><?= e($sessaoChecklist['motorista_nome']) ?></span>
  </div>
</header>

<main>
  <div class="progress-bar">
    <span class="count" id="progressCount">0/<?= count($itens) ?> itens</span>
    <div class="track"><div class="fill" id="progressFill"></div></div>
  </div>

  <div id="itemsContainer"></div>

  <p class="note">Você pode tirar mais de uma foto por item (ex: a mesma cinta de ângulos diferentes). As fotos são analisadas automaticamente depois — se algum item der "Crítico", o gestor já é avisado.</p>
</main>

<div class="summary">
  <div class="counts"></div>
  <button class="send-btn full" id="btnEnviar">Enviar checklist</button>
</div>

<script>
  window.ITENS = <?= json_encode($itens, JSON_UNESCAPED_UNICODE) ?>;
  window.FOTOS_EXISTENTES = <?= json_encode($fotosExistentes, JSON_UNESCAPED_UNICODE) ?>;
  window.CHECKLIST_ID = <?= (int)$sessaoChecklist['id'] ?>;
  window.MAX_FOTOS_POR_ITEM = <?= (int)MAX_FOTOS_POR_ITEM ?>;
</script>
<script src="/assets/js/checklist.js"></script>

</body>
</html>
