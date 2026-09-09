<?php
declare(strict_types=1);

require_once __DIR__ . '/VisionAnalysisException.php';
require_once __DIR__ . '/VisionPrompt.php';

class GroqVisionException extends VisionAnalysisException {}

class GroqVision
{
    private const ENDPOINT = 'https://api.groq.com/openai/v1/chat/completions';

    /** @return array{status: string, observacao: string, classificacao: ?string, vida_util_percentual: ?int} */
    public static function analisarFoto(string $caminhoArquivo, string $mimeType, string $nomeItem, ?string $criterios = null): array
    {
        if (GROQ_API_KEY === '') {
            throw new GroqVisionException('GROQ_API_KEY não configurada no .env');
        }

        $base64 = base64_encode(file_get_contents($caminhoArquivo));
        $dataUri = "data:{$mimeType};base64,{$base64}";

        $prompt = montar_prompt_analise_foto($nomeItem, $criterios);

        $payload = [
            'model' => GROQ_MODEL,
            'messages' => [[
                'role' => 'user',
                'content' => [
                    ['type' => 'text', 'text' => $prompt],
                    ['type' => 'image_url', 'image_url' => ['url' => $dataUri]],
                ],
            ]],
            'response_format' => ['type' => 'json_object'],
        ];

        $ch = curl_init(self::ENDPOINT);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . GROQ_API_KEY,
            ],
            CURLOPT_TIMEOUT => 45,
        ]);
        $resposta = curl_exec($ch);
        $erroCurl = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($resposta === false) {
            throw new GroqVisionException('Falha ao conectar na API do Groq: ' . $erroCurl);
        }

        $dados = json_decode($resposta, true);

        if ($httpCode !== 200) {
            $msg = $dados['error']['message'] ?? $resposta;
            throw new GroqVisionException("API do Groq retornou erro ({$httpCode}): {$msg}");
        }

        $textoResposta = $dados['choices'][0]['message']['content'] ?? null;

        if ($textoResposta === null) {
            throw new GroqVisionException('Resposta do Groq não trouxe texto.');
        }

        $limpo = trim(preg_replace('/```json|```/', '', $textoResposta));
        return normalizar_resposta_ia(json_decode($limpo, true));
    }
}
