<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$gestor = exigir_login_gestor();
$paginaAtual = 'motoristas';
$erro = null;
$sucesso = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validar_csrf_token($_POST['csrf_token'] ?? null)) {
        die('Token CSRF inválido.');
    }

    if (isset($_POST['acao']) && $_POST['acao'] === 'toggle') {
        $stmt = db()->prepare('UPDATE motoristas SET ativo = 1 - ativo WHERE id = ?');
        $stmt->execute([(int)$_POST['id']]);
        header('Location: /painel/motoristas.php');
        exit;
    }

    if (isset($_POST['acao']) && $_POST['acao'] === 'criar') {
        $nome = trim($_POST['nome'] ?? '');
        $pin = trim($_POST['pin'] ?? '');

        if ($nome === '') {
            $erro = 'Informe o nome do motorista.';
        } elseif (!preg_match('/^\d{4}$/', $pin)) {
            $erro = 'O PIN precisa ter exatamente 4 dígitos numéricos.';
        } else {
            try {
                $stmt = db()->prepare('INSERT INTO motoristas (nome, pin) VALUES (?, ?)');
                $stmt->execute([$nome, $pin]);
                $sucesso = 'Motorista cadastrado.';
            } catch (PDOException $e) {
                $erro = str_contains($e->getMessage(), 'Duplicate') ? 'Esse PIN já está em uso.' : 'Erro ao cadastrar motorista.';
            }
        }
    }
}

$motoristas = db()->query('SELECT * FROM motoristas ORDER BY ativo DESC, nome ASC')->fetchAll();
$csrf = gerar_csrf_token();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Motoristas</title>
<link rel="icon" type="image/png" href="/assets/img/favicon.png">
<link rel="stylesheet" href="/assets/css/style.css?v=2">
</head>
<body>

<?php require __DIR__ . '/../includes/painel_topo.php'; ?>

<main>
  <?php if ($erro): ?><p class="alerta-erro"><?= e($erro) ?></p><?php endif; ?>
  <?php if ($sucesso): ?><p class="alerta-sucesso"><?= e($sucesso) ?></p><?php endif; ?>

  <form method="post" class="form-painel" style="margin-bottom:24px;">
    <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
    <input type="hidden" name="acao" value="criar">
    <label class="campo"><span>Nome do motorista</span><input type="text" name="nome" required></label>
    <label class="campo"><span>PIN (4 dígitos, o motorista usa pra entrar)</span><input type="text" name="pin" pattern="\d{4}" maxlength="4" required></label>
    <button type="submit" class="send-btn">Cadastrar motorista</button>
  </form>

  <table class="lista">
    <thead><tr><th>Nome</th><th>PIN</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($motoristas as $m): ?>
        <tr>
          <td><?= e($m['nome']) ?></td>
          <td><?= e($m['pin']) ?></td>
          <td><?= $m['ativo'] ? 'Ativo' : 'Inativo' ?></td>
          <td>
            <form method="post" style="display:inline;">
              <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
              <input type="hidden" name="acao" value="toggle">
              <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
              <button type="submit" class="btn-secundario"><?= $m['ativo'] ? 'Desativar' : 'Ativar' ?></button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>

</body>
</html>
