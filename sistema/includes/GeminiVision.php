<?php
declare(strict_types=1);

require_once __DIR__ . '/VisionAnalysisException.php';
require_once __DIR__ . '/VisionPrompt.php';

class GeminiVisionException extends VisionAnalysisException {}

class GeminiVision
{
    private const ENDPOINT_BASE = 'https://generativelanguage.googleapis.com/v1beta/models/';

    /**
     * Envia a foto de um item de checklist pro Gemini e pede uma
     * classificação estruturada (ok / atencao / critico) com observação curta.
     *
     * @return array{status: string, observacao: string, classificacao: ?string, vida_util_percentual: ?int}
     */
    public static function analisarFoto(string $caminhoArquivo, string $mimeType, string $nomeItem, ?string $criterios = null): array
    {
        if (GEMINI_API_KEY === '') {
            throw new GeminiVisionException('GEMINI_API_KEY não configurada no .env');
        }

        $base64 = base64_encode(file_get_contents($caminhoArquivo));

        $prompt = montar_prompt_analise_foto($nomeItem, $criterios);

        $payload = [
            'contents' => [[
                'parts' => [
                    ['inline_data' => ['mime_type' => $mimeType, 'data' => $base64]],
                    ['text' => $prompt],
                ],
            ]],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'thinkingConfig' => ['thinkingBudget' => 0],
            ],
        ];

        $endpoint = self::ENDPOINT_BASE . GEMINI_MODEL . ':generateContent';

        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'X-goog-api-key: ' . GEMINI_API_KEY,
            ],
            CURLOPT_TIMEOUT => 45,
        ]);
        $resposta = curl_exec($ch);
        $erroCurl = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($resposta === false) {
            throw new GeminiVisionException('Falha ao conectar na API do Gemini: ' . $erroCurl);
        }

        $dados = json_decode($resposta, true);

        if ($httpCode !== 200) {
            $msg = $dados['error']['message'] ?? $resposta;
            throw new GeminiVisionException("API do Gemini retornou erro ({$httpCode}): {$msg}");
        }

        $textoResposta = $dados['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if ($textoResposta === null) {
            $motivo = $dados['candidates'][0]['finishReason'] ?? 'sem candidates na resposta';
            throw new GeminiVisionException('Resposta da IA não trouxe texto (' . $motivo . ').');
        }

        $limpo = trim(preg_replace('/```json|```/', '', $textoResposta));
        return normalizar_resposta_ia(json_decode($limpo, true));
    }
}
