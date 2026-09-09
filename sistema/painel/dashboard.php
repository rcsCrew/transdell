<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$gestor = exigir_login_gestor();
$paginaAtual = 'dashboard';

$filtroStatus = $_GET['status'] ?? '';
$filtroPlaca = normalizar_placa($_GET['placa'] ?? '');

$where = ['c.status = "enviado"'];
$params = [];
if (in_array($filtroStatus, ['ok', 'atencao', 'critico'], true)) {
    $coluna = ['ok' => 'total_ok', 'atencao' => 'total_atencao', 'critico' => 'total_critico'][$filtroStatus];
    $where[] = "c.{$coluna} > 0";
}
if ($filtroPlaca !== '') {
    $where[] = 'c.placa LIKE ?';
    $params[] = '%' . $filtroPlaca . '%';
}
$whereSql = implode(' AND ', $where);

$stmt = db()->prepare("
    SELECT c.id, c.placa, c.km, c.analise_concluida, c.total_ok, c.total_atencao, c.total_critico, c.enviado_em, m.nome AS motorista_nome
    FROM checklists c
    JOIN motoristas m ON m.id = c.motorista_id
    WHERE {$whereSql}
    ORDER BY c.enviado_em DESC
    LIMIT 100
");
$stmt->execute($params);
$checklists = $stmt->fetchAll();

$resumo = db()->query('
    SELECT
      SUM(CASE WHEN status = "enviado" AND DATE(enviado_em) = CURDATE() THEN 1 ELSE 0 END) AS hoje,
      SUM(CASE WHEN status = "enviado" AND total_critico > 0 THEN 1 ELSE 0 END) AS com_critico,
      SUM(CASE WHEN status = "rascunho" THEN 1 ELSE 0 END) AS em_andamento,
      SUM(CASE WHEN status = "enviado" AND analise_concluida = 0 THEN 1 ELSE 0 END) AS processando
    FROM checklists
')->fetch();

$criticosAbertos = (int)db()->query('
    SELECT COUNT(*) c FROM checklist_itens WHERE status = "critico" AND resolvido = 0
')->fetch()['c'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel do gestor — Checklists</title>
<link rel="icon" type="image/png" href="/assets/img/favicon.png">
<link rel="stylesheet" href="/assets/css/style.css?v=2">
</head>
<body>

<?php require __DIR__ . '/../includes/painel_topo.php'; ?>

<main>
  <div class="cards-resumo">
    <div class="card-resumo"><div class="num"><?= (int)$resumo['hoje'] ?></div><div class="lbl">Enviados hoje</div></div>
    <div class="card-resumo crit"><div class="num"><?= $criticosAbertos ?></div><div class="lbl">Itens críticos em aberto</div></div>
    <div class="card-resumo warn"><div class="num"><?= (int)$resumo['processando'] ?></div><div class="lbl">Analisando fotos agora</div></div>
    <div class="card-resumo"><div class="num"><?= (int)$resumo['em_andamento'] ?></div><div class="lbl">Motorista preenchendo</div></div>
    <div class="card-resumo"><div class="num"><?= (int)$resumo['com_critico'] ?></div><div class="lbl">Checklists com crítico</div></div>
  </div>

  <form class="filtros" method="get">
    <select name="status">
      <option value="">Todos os status</option>
      <option value="critico" <?= $filtroStatus === 'critico' ? 'selected' : '' ?>>Com crítico</option>
      <option value="atencao" <?= $filtroStatus === 'atencao' ? 'selected' : '' ?>>Com atenção</option>
      <option value="ok" <?= $filtroStatus === 'ok' ? 'selected' : '' ?>>Só OK</option>
    </select>
    <input type="text" name="placa" placeholder="Filtrar por placa" value="<?= e($filtroPlaca) ?>">
    <button type="submit" class="btn-secundario">Filtrar</button>
  </form>

  <table class="lista">
    <thead>
      <tr>
        <th>Placa</th>
        <th>Motorista</th>
        <th>Enviado em</th>
        <th>OK</th>
        <th>Atenção</th>
        <th>Crítico</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($checklists)): ?>
        <tr><td colspan="7">Nenhum checklist encontrado.</td></tr>
      <?php endif; ?>
      <?php foreach ($checklists as $c): ?>
        <tr>
          <td><?= e($c['placa']) ?></td>
          <td><?= e($c['motorista_nome']) ?></td>
          <td><?= e(date('d/m/Y H:i', strtotime($c['enviado_em']))) ?></td>
          <?php if (!$c['analise_concluida']): ?>
            <td colspan="3"><span class="badge warn">Analisando...</span></td>
          <?php else: ?>
            <td><?= (int)$c['total_ok'] ?></td>
            <td><?= (int)$c['total_atencao'] ?></td>
            <td><?= (int)$c['total_critico'] ?></td>
          <?php endif; ?>
          <td><a href="/painel/detalhe.php?id=<?= (int)$c['id'] ?>">Ver</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>

</body>
</html>
