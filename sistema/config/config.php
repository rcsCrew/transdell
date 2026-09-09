<?php
declare(strict_types=1);

function carregar_env(string $caminho): array
{
    if (!is_file($caminho)) {
        throw new RuntimeException("Arquivo .env não encontrado em: {$caminho}. Copie .env.example para .env e preencha.");
    }

    $valores = [];
    foreach (file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
        $linha = trim($linha);
        if ($linha === '' || str_starts_with($linha, '#')) {
            continue;
        }
        [$chave, $valor] = array_pad(explode('=', $linha, 2), 2, '');
        $valores[trim($chave)] = trim($valor);
    }
    return $valores;
}

$env = carregar_env(__DIR__ . '/../.env');

define('DB_HOST', $env['DB_HOST'] ?? '127.0.0.1');
define('DB_PORT', $env['DB_PORT'] ?? '3306');
define('DB_NAME', $env['DB_NAME'] ?? 'sistema_do_paulo');
define('DB_USER', $env['DB_USER'] ?? 'root');
define('DB_PASS', $env['DB_PASS'] ?? '');

define('VISION_PROVIDER', $env['VISION_PROVIDER'] ?? 'gemini');

define('ANTHROPIC_API_KEY', $env['ANTHROPIC_API_KEY'] ?? '');
define('ANTHROPIC_MODEL', $env['ANTHROPIC_MODEL'] ?? 'claude-sonnet-5');

define('GEMINI_API_KEY', $env['GEMINI_API_KEY'] ?? '');
define('GEMINI_MODEL', $env['GEMINI_MODEL'] ?? 'gemini-flash-latest');

define('OPENROUTER_API_KEY', $env['OPENROUTER_API_KEY'] ?? '');
define('OPENROUTER_MODEL', $env['OPENROUTER_MODEL'] ?? 'dots-studio/dots-3-note-preview:free');

define('GROQ_API_KEY', $env['GROQ_API_KEY'] ?? '');
define('GROQ_MODEL', $env['GROQ_MODEL'] ?? 'meta-llama/llama-4-scout-17b-16e-instruct');

// Contenção anti-loop / anti-custo-descontrolado: máximo de chamadas de IA
// por dia (soma de todos os provedores) e máximo de fotos por item.
define('LIMITE_IA_DIARIO', (int)($env['LIMITE_IA_DIARIO'] ?? 500));
define('MAX_FOTOS_POR_ITEM', (int)($env['MAX_FOTOS_POR_ITEM'] ?? 6));

define('MAIL_HOST', $env['MAIL_HOST'] ?? '127.0.0.1');
define('MAIL_PORT', (int)($env['MAIL_PORT'] ?? 1025));
define('MAIL_USER', $env['MAIL_USER'] ?? '');
define('MAIL_PASS', $env['MAIL_PASS'] ?? '');
define('MAIL_ENCRYPTION', $env['MAIL_ENCRYPTION'] ?? '');
define('MAIL_FROM', $env['MAIL_FROM'] ?? 'checklist@frota.local');
define('MAIL_FROM_NOME', $env['MAIL_FROM_NOME'] ?? 'Checklist de Frota');
define('MAIL_TO', $env['MAIL_TO'] ?? '');

define('APP_URL', $env['APP_URL'] ?? 'http://localhost');
define('UPLOAD_DIR', __DIR__ . '/../uploads/fotos');
define('UPLOAD_URL', '/uploads/fotos');

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../php-error.log');

date_default_timezone_set('America/Sao_Paulo');

if (session_status() === PHP_SESSION_NONE) {
    session_name('frota_sessao');
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ]);
}
