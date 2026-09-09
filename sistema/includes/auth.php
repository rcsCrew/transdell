<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

function gestor_logado(): ?array
{
    return $_SESSION['gestor'] ?? null;
}

function exigir_login_gestor(): array
{
    $gestor = gestor_logado();
    if ($gestor === null) {
        header('Location: /painel/login.php');
        exit;
    }
    return $gestor;
}

function tentar_login_gestor(string $email, string $senha): bool
{
    $stmt = db()->prepare('SELECT id, nome, email, senha_hash FROM usuarios_gestor WHERE email = ?');
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['gestor'] = [
        'id' => $usuario['id'],
        'nome' => $usuario['nome'],
        'email' => $usuario['email'],
    ];
    return true;
}

function logout_gestor(): void
{
    unset($_SESSION['gestor']);
    session_regenerate_id(true);
}

function motorista_por_pin(string $pin): ?array
{
    $stmt = db()->prepare('SELECT id, nome FROM motoristas WHERE pin = ? AND ativo = 1');
    $stmt->execute([$pin]);
    $motorista = $stmt->fetch();
    return $motorista ?: null;
}

function veiculo_cadastrado(string $placa): bool
{
    $stmt = db()->prepare('SELECT id FROM caminhoes WHERE placa = ? AND ativo = 1');
    $stmt->execute([$placa]);
    return (bool)$stmt->fetch();
}

function gerar_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validar_csrf_token(?string $token): bool
{
    return is_string($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
