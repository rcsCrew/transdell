<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$gestor = exigir_login_gestor();
$paginaAtual = 'dashboard';

$checklistId = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolver_item_id'])) {
    if (!validar_csrf_token($_POST['csrf_token'] ?? null)) {
        die('Token CSRF inválido.');
    }
    $itemId = (int)$_POST['resolver_item_id'];
    $stmt = db()->prepare('UPDATE checklist_itens SET resolvido = 1, resolvido_em = NOW(), resolvido_por = ? WHERE id = ? AND checklist_id = ?');
    $stmt->execute([$gestor['id'], $itemId, $checklistId]);
    header('Location: /painel/detalhe.php?id=' . $checklistId);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['classificar_foto_id'])) {
    if (!validar_csrf_token($_POST['csrf_token'] ?? null)) {
        die('Token CSRF inválido.');
    }
    $fotoId = (int)$_POST['classificar_foto_id'];
    $novoStatus = $_POST['novo_status'] ?? '';
    if (in_array($novoStatus, ['ok', 'atencao', 'critico'], true)) {
        $stmt = db()->prepare('
            SELECT f.checklist_item_id FROM checklist_fotos f
            JOIN checklist_itens i ON i.id = f.checklist_item_id
            WHERE f.id = ? AND i.checklist_id = ?
        ');
        $stmt->execute([$fotoId, $checklistId]);
        $checklistItemId = $stmt->fetchColumn();

        if ($checklistItemId) {
            $observacao = 'Classificado manualmente por ' . $gestor['nome'] . ' (IA não conseguiu analisar após várias tentativas).';
            $stmt = db()->prepare('UPDATE checklist_fotos SET status = ?, observacao_ia = ?, precisa_revisao_manual = 0 WHERE id = ?');
            $stmt->execute([$novoStatus, $observacao, $fotoId]);
            atualizar_status_agregado_item((int)$checklistItemId);
        }
    }
    header('Location: /painel/detalhe.php?id=' . $checklistId);
    exit;
}

$stmt = db()->prepare('
    SELECT c.*, m.nome AS motorista_nome
    FROM checklists c JOIN motoristas m ON m.id = c.motorista_id
    WHERE c.id = ?
');
$stmt->execute([$checklistId]);
$checklist = $stmt->fetch();

if (!$checklist) {
    die('Checklist não encontrado.');
}

$stmt = db()->prepare('SELECT * FROM checklist_itens WHERE checklist_id = ? ORDER BY id ASC');
$stmt->execute([$checklistId]);
$itens = $stmt->fetchAll();

$fotosPorItem = [];
if (!empty($itens)) {
    $idsItens = array_column($itens, 'id');
    $placeholders = implode(',', array_fill(0, count($idsItens), '?'));
    $stmt = db()->prepare("SELECT * FROM checklist_fotos WHERE checklist_item_id IN ($placeholders) ORDER BY id ASC");
    $stmt->execute($idsItens);
    foreach ($stmt->fetchAll() as $foto) {
        $fotosPorItem[$foto['checklist_item_id']][] = $foto;
    }
}

$csrf = gerar_csrf_token();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checklist #<?= (int)$checklist['id'] ?> — Placa <?= e($checklist['placa']) ?></title>
<link rel="icon" type="image/png" href="/assets/img/favicon.png">
<link rel="stylesheet" href="/assets/css/style.css?v=2">
</head>
<body>

<?php require __DIR__ . '/../includes/painel_topo.php'; ?>

<main>
  <p><a href="/painel/dashboard.php" style="color:var(--paper-dim);text-decoration:none;">&larr; Voltar</a></p>

  <div class="cards-resumo">
    <div class="card-resumo"><div class="num"><?= e($checklist['placa']) ?></div><div class="lbl">Placa</div></div>
    <div class="card-resumo"><div class="num" style="font-size:16px;"><?= e($checklist['motorista_nome']) ?></div><div class="lbl">Motorista</div></div>
    <div class="card-resumo"><div class="num" style="font-size:16px;"><?= $checklist['enviado_em'] ? e(date('d/m/Y H:i', strtotime($checklist['enviado_em']))) : 'Em andamento' ?></div><div class="lbl">Envio</div></div>
    <?php if ($checklist['km']): ?>
    <div class="card-resumo"><div class="num"><?= (int)$checklist['km'] ?></div><div class="lbl">KM</div></div>
    <?php endif; ?>
  </div>

  <?php if ($checklist['latitude'] && $checklist['longitude']): ?>
    <p><a href="https://www.google.com/maps?q=<?= (float)$checklist['latitude'] ?>,<?= (float)$checklist['longitude'] ?>" target="_blank" rel="noopener" style="color:var(--amber);">Ver localização no mapa</a></p>
  <?php endif; ?>

  <?php if ($checklist['status'] === 'enviado' && !$checklist['analise_concluida']): ?>
    <p class="alerta-sucesso" style="background:rgba(242,167,27,0.15);border-color:var(--amber);color:var(--amber);">
      <span class="spinner"></span> Análise em andamento — as fotos são processadas automaticamente a cada minuto.
    </p>
  <?php endif; ?>

  <?php foreach ($itens as $item): $badge = badge_info($item['status']); $fotos = $fotosPorItem[$item['id']] ?? []; ?>
    <div class="item <?= e($badge['cls']) ?>">
      <div class="item-head">
        <span class="item-name"><?= e($item['item_nome']) ?></span>
        <span class="badge <?= e($badge['cls']) ?>"><?= e($badge['label']) ?> <?= count($fotos) > 1 ? '· ' . count($fotos) . ' fotos' : '' ?></span>
      </div>
      <?php foreach ($fotos as $i => $foto): ?>
        <div style="margin-top:10px;">
          <a href="<?= e(foto_url($foto['foto_path'])) ?>" target="_blank" rel="noopener">
            <img class="foto-grande" src="<?= e(foto_url($foto['foto_path'])) ?>">
          </a>
          <?php if ($foto['status'] !== null && $foto['classificacao']): $badgeFoto = classificacao_condicao_info($foto['classificacao']); ?>
            <div class="analysis show <?= e($badgeFoto['cls']) ?>">
              <span class="label">Foto <?= $i + 1 ?> — <?= e($badgeFoto['label']) ?><?= $foto['vida_util_percentual'] !== null ? ' · ~' . (int)$foto['vida_util_percentual'] . '% de vida útil' : '' ?></span><?= e($foto['observacao_ia']) ?>
            </div>
          <?php elseif ($foto['status'] !== null): $badgeFoto = badge_info($foto['status']); ?>
            <div class="analysis show <?= e($badgeFoto['cls']) ?>">
              <span class="label">Foto <?= $i + 1 ?> — <?= e($badgeFoto['label']) ?></span><?= e($foto['observacao_ia']) ?>
            </div>
          <?php elseif ($foto['precisa_revisao_manual']): ?>
            <div class="foto-detalhe-card">
              <span class="label">Foto <?= $i + 1 ?> — IA não conseguiu analisar, classifique você mesmo:</span>
              <form method="post" class="manual-btns">
                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                <input type="hidden" name="classificar_foto_id" value="<?= (int)$foto['id'] ?>">
                <button type="submit" name="novo_status" value="ok" style="color:var(--ok);">OK</button>
                <button type="submit" name="novo_status" value="atencao" style="color:var(--warn);">Atenção</button>
                <button type="submit" name="novo_status" value="critico" style="color:var(--danger);">Crítico</button>
              </form>
            </div>
          <?php else: ?>
            <div class="analysis show">
              <span class="spinner"></span> Foto <?= $i + 1 ?> — aguardando análise automática...
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
      <?php if ($item['status'] === 'critico'): ?>
        <?php if ($item['resolvido']): ?>
          <p class="note" style="text-align:left;margin-top:8px;">Resolvido em <?= e(date('d/m/Y H:i', strtotime($item['resolvido_em']))) ?></p>
        <?php else: ?>
          <form method="post" style="margin-top:10px;">
            <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
            <input type="hidden" name="resolver_item_id" value="<?= (int)$item['id'] ?>">
            <button type="submit" class="btn-secundario">Marcar como resolvido</button>
          </form>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</main>

</body>
</html>
