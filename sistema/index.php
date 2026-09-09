<?php
declare(strict_types=1);
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin = trim($_POST['pin'] ?? '');
    $placa = normalizar_placa($_POST['placa'] ?? '');
    $km = trim($_POST['km'] ?? '');

    $motorista = motorista_por_pin($pin);

    if (!$motorista) {
        $erro = 'PIN não encontrado. Confira com o gestor da frota.';
    } elseif ($placa === '') {
        $erro = 'Informe a placa do caminhão.';
    } elseif (!veiculo_cadastrado($placa)) {
        $erro = "Placa {$placa} não cadastrada no sistema. Peça pro gestor da frota cadastrar esse veículo (menu Veículos) antes de iniciar o checklist.";
    } else {
        $stmt = db()->prepare('INSERT INTO checklists (motorista_id, placa, km) VALUES (?, ?, ?)');
        $stmt->execute([$motorista['id'], $placa, $km !== '' ? (int)$km : null]);
        $checklistId = (int)db()->lastInsertId();

        $_SESSION['checklist_atual'] = [
            'id' => $checklistId,
            'motorista_id' => $motorista['id'],
            'motorista_nome' => $motorista['nome'],
            'placa' => $placa,
        ];

        header('Location: /checklist.php');
        exit;
    }
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
<body class="tela-entrada">

<header>
  <img class="logo-img" src="/assets/img/transdell-logo.png" alt="Transdell">
  <p class="eyebrow">Saída de viagem</p>
  <h1>Checklist Fotográfico</h1>
</header>

<main class="entrada-form">
  <?php if ($erro): ?>
    <p class="alerta-erro"><?= e($erro) ?></p>
  <?php endif; ?>

  <form method="post">
    <label class="campo">
      <span>Seu PIN</span>
      <input type="password" inputmode="numeric" pattern="[0-9]{4}" maxlength="4" name="pin" placeholder="****" required autofocus>
    </label>
    <label class="campo">
      <span>Placa do cavalo</span>
      <input type="text" name="placa" placeholder="ABC1D23" maxlength="8" required style="text-transform:uppercase">
    </label>
    <label class="campo">
      <span>KM atual (opcional)</span>
      <input type="number" name="km" placeholder="0">
    </label>
    <button type="submit" class="send-btn full">Iniciar checklist</button>
  </form>

  <p class="note">Não tem PIN cadastrado? Fale com o gestor da frota.</p>
</main>

</body>
</html>
