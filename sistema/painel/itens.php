<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$gestor = exigir_login_gestor();
$paginaAtual = 'itens';
$erro = null;
$sucesso = null;

function gerar_chave_unica(string $nome): string
{
    return strtolower(preg_replace('/[^a-z0-9]+/', '', str_replace(
        ['á','à','â','ã','é','ê','í','ó','ô','õ','ú','ç'],
        ['a','a','a','a','e','e','i','o','o','o','u','c'],
        strtolower($nome)
    )));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validar_csrf_token($_POST['csrf_token'] ?? null)) {
        die('Token CSRF inválido.');
    }
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'toggle') {
        $stmt = db()->prepare('UPDATE itens_padrao SET ativo = 1 - ativo WHERE id = ?');
        $stmt->execute([(int)$_POST['id']]);
        header('Location: /painel/itens.php');
        exit;
    }

    if ($acao === 'excluir') {
        $stmt = db()->prepare('DELETE FROM itens_padrao WHERE id = ?');
        $stmt->execute([(int)$_POST['id']]);
        header('Location: /painel/itens.php');
        exit;
    }

    if ($acao === 'criar') {
        $nome = trim($_POST['nome'] ?? '');
        $ordem = (int)($_POST['ordem'] ?? 0);
        $criterios = trim($_POST['criterios_analise'] ?? '');
        $chave = gerar_chave_unica($nome);

        if ($nome === '' || $chave === '') {
            $erro = 'Informe um nome válido pro item.';
        } else {
            try {
                $stmt = db()->prepare('INSERT INTO itens_padrao (chave, nome, ordem, criterios_analise) VALUES (?, ?, ?, ?)');
                $stmt->execute([$chave, $nome, $ordem, $criterios !== '' ? $criterios : null]);
                $sucesso = 'Item cadastrado.';
            } catch (PDOException $e) {
                $erro = str_contains($e->getMessage(), 'Duplicate') ? 'Já existe um item parecido cadastrado.' : 'Erro ao cadastrar item.';
            }
        }
    }

    if ($acao === 'editar') {
        $id = (int)($_POST['id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $ordem = (int)($_POST['ordem'] ?? 0);
        $criterios = trim($_POST['criterios_analise'] ?? '');

        if ($nome === '') {
            $erro = 'Informe um nome válido pro item.';
        } else {
            $stmt = db()->prepare('UPDATE itens_padrao SET nome = ?, ordem = ?, criterios_analise = ? WHERE id = ?');
            $stmt->execute([$nome, $ordem, $criterios !== '' ? $criterios : null, $id]);
            $sucesso = 'Item atualizado.';
        }
    }
}

$itemEditando = null;
if (isset($_GET['editar'])) {
    $stmt = db()->prepare('SELECT * FROM itens_padrao WHERE id = ?');
    $stmt->execute([(int)$_GET['editar']]);
    $itemEditando = $stmt->fetch() ?: null;
}

$itens = db()->query('SELECT * FROM itens_padrao ORDER BY ordem ASC, nome ASC')->fetchAll();
$csrf = gerar_csrf_token();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Itens do checklist</title>
<link rel="icon" type="image/png" href="/assets/img/favicon.png">
<link rel="stylesheet" href="/assets/css/style.css?v=2">
</head>
<body>

<?php require __DIR__ . '/../includes/painel_topo.php'; ?>

<main>
  <?php if ($erro): ?><p class="alerta-erro"><?= e($erro) ?></p><?php endif; ?>
  <?php if ($sucesso): ?><p class="alerta-sucesso"><?= e($sucesso) ?></p><?php endif; ?>

  <p class="note" style="text-align:left;">Esses são os itens que aparecem no checklist do motorista. Desativar um item não apaga o histórico já registrado com ele. Deixe "critérios de análise" em branco pra IA só dar OK/Atenção/Crítico — preencha com o que você quer que a IA procure nessa foto (desgaste, rachadura, corrosão, etc) pra ela dar uma avaliação detalhada com classificação (Excelente/Bom/Regular/Crítico) e estimativa de % de vida útil.</p>

  <?php if ($itemEditando): ?>
    <form method="post" class="form-painel" style="margin:16px 0 24px;">
      <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
      <input type="hidden" name="acao" value="editar">
      <input type="hidden" name="id" value="<?= (int)$itemEditando['id'] ?>">
      <label class="campo"><span>Editando: <?= e($itemEditando['nome']) ?></span></label>
      <label class="campo"><span>Nome do item</span><input type="text" name="nome" value="<?= e($itemEditando['nome']) ?>" required></label>
      <label class="campo"><span>Ordem de exibição</span><input type="number" name="ordem" value="<?= (int)$itemEditando['ordem'] ?>"></label>
      <label class="campo">
        <span>Critérios de análise da IA (opcional)</span>
        <textarea name="criterios_analise" rows="5" placeholder="Ex: Verifique rachaduras na base, folga excessiva, corrosão nos parafusos, sinais de vazamento de graxa..." style="width:100%;background:var(--asphalt-2);border:1px solid #2a3550;color:var(--paper);padding:12px;border-radius:8px;font-size:14px;font-family:var(--body);"><?= e($itemEditando['criterios_analise'] ?? '') ?></textarea>
      </label>
      <div style="display:flex;gap:8px;">
        <button type="submit" class="send-btn">Salvar alterações</button>
        <a href="/painel/itens.php" class="btn-secundario" style="text-decoration:none;display:inline-flex;align-items:center;">Cancelar</a>
      </div>
    </form>
  <?php else: ?>
    <form method="post" class="form-painel" style="margin:16px 0 24px;">
      <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
      <input type="hidden" name="acao" value="criar">
      <label class="campo"><span>Nome do item</span><input type="text" name="nome" placeholder="Ex: Freios" required></label>
      <label class="campo"><span>Ordem de exibição</span><input type="number" name="ordem" value="<?= count($itens) + 1 ?>"></label>
      <label class="campo">
        <span>Critérios de análise da IA (opcional)</span>
        <textarea name="criterios_analise" rows="5" placeholder="Ex: Verifique rachaduras na base, folga excessiva, corrosão nos parafusos, sinais de vazamento de graxa..." style="width:100%;background:var(--asphalt-2);border:1px solid #2a3550;color:var(--paper);padding:12px;border-radius:8px;font-size:14px;font-family:var(--body);"></textarea>
      </label>
      <button type="submit" class="send-btn">Adicionar item</button>
    </form>
  <?php endif; ?>

  <table class="lista">
    <thead><tr><th>Ordem</th><th>Nome</th><th>Análise</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($itens as $it): ?>
        <tr>
          <td><?= (int)$it['ordem'] ?></td>
          <td><?= e($it['nome']) ?></td>
          <td><?= $it['criterios_analise'] ? 'Personalizada' : 'Genérica' ?></td>
          <td><?= $it['ativo'] ? 'Ativo' : 'Inativo' ?></td>
          <td style="white-space:nowrap;">
            <a href="/painel/itens.php?editar=<?= (int)$it['id'] ?>" class="btn-secundario" style="text-decoration:none;display:inline-block;">Editar</a>
            <form method="post" style="display:inline;">
              <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
              <input type="hidden" name="acao" value="toggle">
              <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
              <button type="submit" class="btn-secundario"><?= $it['ativo'] ? 'Desativar' : 'Ativar' ?></button>
            </form>
            <form method="post" style="display:inline;" onsubmit="return confirm('Excluir o item \'<?= e(addslashes($it['nome'])) ?>\'? O histórico de checklists já enviados não é afetado.');">
              <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
              <input type="hidden" name="acao" value="excluir">
              <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
              <button type="submit" class="btn-secundario btn-perigo">Excluir</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>

</body>
</html>
