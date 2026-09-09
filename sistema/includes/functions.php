<?php
declare(strict_types=1);

function responder_json(array $dados, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}

function normalizar_placa(string $placa): string
{
    $placa = strtoupper(trim($placa));
    return preg_replace('/[^A-Z0-9]/', '', $placa) ?? '';
}

/**
 * Salva um arquivo de foto enviado por upload, validando tipo e tamanho.
 * Retorna o caminho relativo (a partir de UPLOAD_DIR) salvo no banco.
 */
function salvar_foto_upload(array $arquivo): string
{
    if (!isset($arquivo['error']) || $arquivo['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Falha no upload da foto.');
    }

    $tamanhoMaximo = 8 * 1024 * 1024; // 8MB
    if ($arquivo['size'] > $tamanhoMaximo) {
        throw new RuntimeException('Foto maior que 8MB.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($arquivo['tmp_name']);
    $extensoesPermitidas = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    if (!isset($extensoesPermitidas[$mime])) {
        throw new RuntimeException('Tipo de arquivo não permitido. Envie uma foto (JPEG, PNG ou WEBP).');
    }

    $subpasta = date('Y/m');
    $pastaDestino = UPLOAD_DIR . '/' . $subpasta;
    if (!is_dir($pastaDestino) && !mkdir($pastaDestino, 0755, true) && !is_dir($pastaDestino)) {
        throw new RuntimeException('Não foi possível criar a pasta de upload.');
    }

    $nomeArquivo = bin2hex(random_bytes(16)) . '.' . $extensoesPermitidas[$mime];
    $caminhoCompleto = $pastaDestino . '/' . $nomeArquivo;

    if (!move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
        throw new RuntimeException('Não foi possível salvar a foto no servidor.');
    }

    return $subpasta . '/' . $nomeArquivo;
}

function foto_url(string $caminhoRelativo): string
{
    return UPLOAD_URL . '/' . ltrim($caminhoRelativo, '/');
}

function badge_info(?string $status): array
{
    return match ($status) {
        'ok' => ['label' => 'OK', 'cls' => 'ok'],
        'atencao' => ['label' => 'Atenção', 'cls' => 'warn'],
        'critico' => ['label' => 'Crítico', 'cls' => 'crit'],
        default => ['label' => 'Pendente', 'cls' => 'pendente'],
    };
}

/** Rótulo + cor pra classificação de condição de qualquer item com critérios personalizados. */
function classificacao_condicao_info(string $classificacao): array
{
    return match ($classificacao) {
        'excelente' => ['label' => 'Excelente', 'cls' => 'ok'],
        'bom' => ['label' => 'Bom', 'cls' => 'ok'],
        'regular' => ['label' => 'Regular', 'cls' => 'warn'],
        'critico' => ['label' => 'Crítico', 'cls' => 'crit'],
        default => ['label' => ucfirst($classificacao), 'cls' => 'pendente'],
    };
}

/**
 * Recalcula o status agregado de um item (o pior status entre todas as
 * fotos dele) e grava em checklist_itens.status. Retorna null se ainda tem
 * foto sem classificação (esperando o motorista classificar na mão).
 * Se o agregado virar "critico", reabre o item (tira o "resolvido").
 */
function atualizar_status_agregado_item(int $checklistItemId): ?string
{
    $stmt = db()->prepare('SELECT status FROM checklist_fotos WHERE checklist_item_id = ?');
    $stmt->execute([$checklistItemId]);
    $statuses = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($statuses) || in_array(null, $statuses, true)) {
        $agregado = null;
    } elseif (in_array('critico', $statuses, true)) {
        $agregado = 'critico';
    } elseif (in_array('atencao', $statuses, true)) {
        $agregado = 'atencao';
    } else {
        $agregado = 'ok';
    }

    if ($agregado === 'critico') {
        $stmt = db()->prepare('UPDATE checklist_itens SET status = ?, resolvido = 0, resolvido_em = NULL, resolvido_por = NULL WHERE id = ?');
    } else {
        $stmt = db()->prepare('UPDATE checklist_itens SET status = ? WHERE id = ?');
    }
    $stmt->execute([$agregado, $checklistItemId]);

    return $agregado;
}

function e(?string $valor): string
{
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}
