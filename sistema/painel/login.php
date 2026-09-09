<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (gestor_logado()) {
    header('Location: /painel/dashboard.php');
    exit;
}

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = (string)($_POST['senha'] ?? '');

    if (tentar_login_gestor($email, $senha)) {
        header('Location: /painel/dashboard.php');
        exit;
    }
    $erro = 'E-mail ou senha inválidos.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel do gestor — Login</title>
<link rel="icon" type="image/png" href="/assets/img/favicon.png">
<link rel="stylesheet" href="/assets/css/style.css?v=2">
</head>
<body class="tela-entrada">

<header>
  <img class="logo-img" src="/assets/img/transdell-logo.png" alt="Transdell">
  <p class="eyebrow">Painel do gestor</p>
  <h1>Entrar</h1>
</header>

<main class="entrada-form">
  <?php if ($erro): ?>
    <p class="alerta-erro"><?= e($erro) ?></p>
  <?php endif; ?>

  <form method="post">
    <label class="campo">
      <span>E-mail</span>
      <input type="email" name="email" required autofocus>
    </label>
    <label class="campo">
      <span>Senha</span>
      <input type="password" name="senha" required>
    </label>
    <button type="submit" class="send-btn full">Entrar</button>
  </form>
</main>

</body>
</html>
