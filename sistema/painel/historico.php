<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$gestor = exigir_login_gestor();
$paginaAtual = 'historico';

$placa = normalizar_placa($_GET['placa'] ?? '');

$placasDisponiveis = db()->query('SELECT DISTINCT placa FROM checklists ORDER BY placa ASC')->fetchAll(PDO::FETCH_COLUMN);

$checklists = [];
if ($placa !== '') {
    $stmt = db()->prepare('
        SELECT c.id, c.km, c.status, c.analise_concluida, c.total_ok, c.total_atencao, c.total_critico, c.criado_em, c.enviado_em, m.nome AS motorista_nome
        FROM checklists c JOIN motoristas m ON m.id = c.motorista_id
        WHERE c.placa = ?
        ORDER BY c.criado_em DESC
    ');
    $stmt->execute([$placa]);
    $checklists = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Histórico por placa</title>
<link rel="icon" type="image/png" href="/assets/img/favicon.png">
<link rel="stylesheet" href="/assets/css/style.css?v=2">
</head>
<body>

<?php require __DIR__ . '/../includes/painel_topo.php'; ?>

<main>
  <form class="filtros" method="get">
    <select name="placa" onchange="this.form.submit()">
      <option value="">Escolha a placa</option>
      <?php foreach ($placasDisponiveis as $p): ?>
        <option value="<?= e($p) ?>" <?= $p === $placa ? 'selected' : '' ?>><?= e($p) ?></option>
      <?php endforeach; ?>
    </select>
  </form>

  <?php if ($placa === ''): ?>
    <p class="note" style="text-align:left;">Escolha uma placa acima pra ver a linha do tempo de checklists — útil pra ver se um item específico vem degradando ao longo das viagens.</p>
  <?php elseif (empty($checklists)): ?>
    <p class="note" style="text-align:left;">Nenhum checklist para essa placa.</p>
  <?php else: ?>
    <table class="lista">
      <thead>
        <tr><th>Data</th><th>Status</th><th>Motorista</th><th>KM</th><th>OK</th><th>Atenção</th><th>Crítico</th><th></th></tr>
      </thead>
      <tbody>
        <?php foreach ($checklists as $c): ?>
          <tr>
            <td><?= e(date('d/m/Y H:i', strtotime($c['criado_em']))) ?></td>
            <td><?= $c['status'] !== 'enviado' ? 'Motorista preenchendo' : ($c['analise_concluida'] ? 'Enviado' : 'Analisando...') ?></td>
            <td><?= e($c['motorista_nome']) ?></td>
            <td><?= $c['km'] ? (int)$c['km'] : '-' ?></td>
            <td><?= (int)$c['total_ok'] ?></td>
            <td><?= (int)$c['total_atencao'] ?></td>
            <td><?= (int)$c['total_critico'] ?></td>
            <td><a href="/painel/detalhe.php?id=<?= (int)$c['id'] ?>">Ver</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</main>

</body>
</html>
