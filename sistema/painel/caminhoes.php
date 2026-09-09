<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$gestor = exigir_login_gestor();
$paginaAtual = 'caminhoes';
$erro = null;
$sucesso = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validar_csrf_token($_POST['csrf_token'] ?? null)) {
        die('Token CSRF inválido.');
    }

    if (isset($_POST['acao']) && $_POST['acao'] === 'toggle') {
        $stmt = db()->prepare('UPDATE caminhoes SET ativo = 1 - ativo WHERE id = ?');
        $stmt->execute([(int)$_POST['id']]);
        header('Location: /painel/caminhoes.php');
        exit;
    }

    if (isset($_POST['acao']) && $_POST['acao'] === 'criar') {
        $placa = normalizar_placa($_POST['placa'] ?? '');
        $modelo = trim($_POST['modelo'] ?? '');

        if ($placa === '') {
            $erro = 'Informe a placa do veículo.';
        } else {
            try {
                $stmt = db()->prepare('INSERT INTO caminhoes (placa, modelo) VALUES (?, ?)');
                $stmt->execute([$placa, $modelo !== '' ? $modelo : null]);
                $sucesso = 'Veículo cadastrado.';
            } catch (PDOException $e) {
                $erro = str_contains($e->getMessage(), 'Duplicate') ? 'Essa placa já está cadastrada.' : 'Erro ao cadastrar veículo.';
            }
        }
    }
}

$caminhoes = db()->query('SELECT * FROM caminhoes ORDER BY ativo DESC, placa ASC')->fetchAll();
$csrf = gerar_csrf_token();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Veículos</title>
<link rel="icon" type="image/png" href="/assets/img/favicon.png">
<link rel="stylesheet" href="/assets/css/style.css?v=2">
</head>
<body>

<?php require __DIR__ . '/../includes/painel_topo.php'; ?>

<main>
  <?php if ($erro): ?><p class="alerta-erro"><?= e($erro) ?></p><?php endif; ?>
  <?php if ($sucesso): ?><p class="alerta-sucesso"><?= e($sucesso) ?></p><?php endif; ?>

  <p class="note" style="text-align:left;">Só placas cadastradas e ativas aqui aparecem liberadas pro motorista iniciar um checklist. Qualquer motorista ativo pode dirigir qualquer veículo ativo — não tem vínculo fixo entre os dois.</p>

  <form method="post" class="form-painel" style="margin:16px 0 24px;">
    <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
    <input type="hidden" name="acao" value="criar">
    <label class="campo"><span>Placa</span><input type="text" name="placa" placeholder="ABC1D23" maxlength="8" required style="text-transform:uppercase"></label>
    <label class="campo"><span>Modelo (opcional)</span><input type="text" name="modelo" placeholder="Ex: Scania R450"></label>
    <button type="submit" class="send-btn">Cadastrar veículo</button>
  </form>

  <table class="lista">
    <thead><tr><th>Placa</th><th>Modelo</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php if (empty($caminhoes)): ?>
        <tr><td colspan="4">Nenhum veículo cadastrado.</td></tr>
      <?php endif; ?>
      <?php foreach ($caminhoes as $c): ?>
        <tr>
          <td><?= e($c['placa']) ?></td>
          <td><?= e($c['modelo'] ?? '-') ?></td>
          <td><?= $c['ativo'] ? 'Ativo' : 'Inativo' ?></td>
          <td>
            <form method="post" style="display:inline;">
              <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
              <input type="hidden" name="acao" value="toggle">
              <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
              <button type="submit" class="btn-secundario"><?= $c['ativo'] ? 'Desativar' : 'Ativar' ?></button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>

</body>
</html>
