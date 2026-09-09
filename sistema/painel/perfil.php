<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$gestor = exigir_login_gestor();
$paginaAtual = '';
$erro = null;
$sucesso = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validar_csrf_token($_POST['csrf_token'] ?? null)) {
        die('Token CSRF inválido.');
    }

    $senhaAtual = (string)($_POST['senha_atual'] ?? '');
    $novaSenha = (string)($_POST['nova_senha'] ?? '');
    $confirmacao = (string)($_POST['confirmacao'] ?? '');

    $stmt = db()->prepare('SELECT senha_hash FROM usuarios_gestor WHERE id = ?');
    $stmt->execute([$gestor['id']]);
    $hashAtual = $stmt->fetchColumn();

    if (!password_verify($senhaAtual, $hashAtual)) {
        $erro = 'Senha atual incorreta.';
    } elseif (strlen($novaSenha) < 8) {
        $erro = 'A nova senha precisa ter pelo menos 8 caracteres.';
    } elseif ($novaSenha !== $confirmacao) {
        $erro = 'A confirmação não bate com a nova senha.';
    } else {
        $stmt = db()->prepare('UPDATE usuarios_gestor SET senha_hash = ? WHERE id = ?');
        $stmt->execute([password_hash($novaSenha, PASSWORD_DEFAULT), $gestor['id']]);
        $sucesso = 'Senha alterada com sucesso.';
    }
}

$csrf = gerar_csrf_token();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Meu perfil</title>
<link rel="icon" type="image/png" href="/assets/img/favicon.png">
<link rel="stylesheet" href="/assets/css/style.css?v=2">
</head>
<body>

<?php require __DIR__ . '/../includes/painel_topo.php'; ?>

<main>
  <h2 style="font-family:var(--display);font-size:16px;text-transform:uppercase;">Alterar senha</h2>
  <?php if ($erro): ?><p class="alerta-erro"><?= e($erro) ?></p><?php endif; ?>
  <?php if ($sucesso): ?><p class="alerta-sucesso"><?= e($sucesso) ?></p><?php endif; ?>

  <form method="post" class="form-painel">
    <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
    <label class="campo"><span>Senha atual</span><input type="password" name="senha_atual" required></label>
    <label class="campo"><span>Nova senha</span><input type="password" name="nova_senha" required minlength="8"></label>
    <label class="campo"><span>Confirmar nova senha</span><input type="password" name="confirmacao" required minlength="8"></label>
    <button type="submit" class="send-btn">Salvar</button>
  </form>
</main>

</body>
</html>
