<?php
declare(strict_types=1);

require_once __DIR__ . '/VisionAnalysisException.php';
require_once __DIR__ . '/VisionPrompt.php';

class ClaudeVisionException extends VisionAnalysisException {}

class ClaudeVision
{
    private const ENDPOINT = 'https://api.anthropic.com/v1/messages';
    private const VERSAO_API = '2023-06-01';

    /**
     * Envia a foto de um item de checklist pra API da Claude e pede uma
     * classificação estruturada (ok / atencao / critico) com observação curta
     * — ou uma avaliação especializada, se o item tiver um tipo_analise
     * dedicado (ex: pneu).
     *
     * @return array{status: string, observacao: string, classificacao: ?string, vida_util_percentual: ?int}
     */
    public static function analisarFoto(string $caminhoArquivo, string $mimeType, string $nomeItem, ?string $criterios = null): array
    {
        if (ANTHROPIC_API_KEY === '') {
            throw new ClaudeVisionException('ANTHROPIC_API_KEY não configurada no .env');
        }

        $base64 = base64_encode(file_get_contents($caminhoArquivo));

        $prompt = montar_prompt_analise_foto($nomeItem, $criterios);

        $payload = [
            'model' => ANTHROPIC_MODEL,
            'max_tokens' => 300,
            'messages' => [[
                'role' => 'user',
                'content' => [
                    ['type' => 'image', 'source' => ['type' => 'base64', 'media_type' => $mimeType, 'data' => $base64]],
                    ['type' => 'text', 'text' => $prompt],
                ],
            ]],
        ];

        $ch = curl_init(self::ENDPOINT);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'x-api-key: ' . ANTHROPIC_API_KEY,
                'anthropic-version: ' . self::VERSAO_API,
            ],
            CURLOPT_TIMEOUT => 30,
        ]);
        $resposta = curl_exec($ch);
        $erroCurl = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($resposta === false) {
            throw new ClaudeVisionException('Falha ao conectar na API da Anthropic: ' . $erroCurl);
        }

        $dados = json_decode($resposta, true);

        if ($httpCode !== 200) {
            $msg = $dados['error']['message'] ?? $resposta;
            throw new ClaudeVisionException("API da Anthropic retornou erro ({$httpCode}): {$msg}");
        }

        $textoResposta = null;
        foreach ($dados['content'] ?? [] as $bloco) {
            if (($bloco['type'] ?? '') === 'text') {
                $textoResposta = $bloco['text'];
                break;
            }
        }

        if ($textoResposta === null) {
            throw new ClaudeVisionException('Resposta da IA não trouxe texto.');
        }

        $limpo = trim(preg_replace('/```json|```/', '', $textoResposta));
        return normalizar_resposta_ia(json_decode($limpo, true));
    }
}
