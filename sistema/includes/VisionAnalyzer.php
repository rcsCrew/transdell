<?php
declare(strict_types=1);

require_once __DIR__ . '/VisionAnalysisException.php';
require_once __DIR__ . '/ClaudeVision.php';
require_once __DIR__ . '/GeminiVision.php';
require_once __DIR__ . '/OpenRouterVision.php';
require_once __DIR__ . '/GroqVision.php';

/**
 * Ponto único de entrada pra análise de foto por IA. Tenta os provedores
 * na ordem definida em VISION_PROVIDER (lista separada por vírgula, ex:
 * "gemini,openrouter,groq") — se um falhar (sem crédito, fora do ar,
 * limite de uso), tenta o próximo antes de desistir. Só quando todos
 * falham é que o motorista cai no modo de classificação manual.
 */
class VisionAnalyzer
{
    /** @return array{status: string, observacao: string, classificacao: ?string, vida_util_percentual: ?int} */
    public static function analisarFoto(string $caminhoArquivo, string $mimeType, string $nomeItem, ?string $criterios = null): array
    {
        $provedores = array_filter(array_map('trim', explode(',', VISION_PROVIDER)));

        if (empty($provedores)) {
            throw new VisionAnalysisException('VISION_PROVIDER não configurado no .env');
        }

        $erros = [];

        foreach ($provedores as $provedor) {
            try {
                return match ($provedor) {
                    'gemini' => GeminiVision::analisarFoto($caminhoArquivo, $mimeType, $nomeItem, $criterios),
                    'openrouter' => OpenRouterVision::analisarFoto($caminhoArquivo, $mimeType, $nomeItem, $criterios),
                    'groq' => GroqVision::analisarFoto($caminhoArquivo, $mimeType, $nomeItem, $criterios),
                    'anthropic' => ClaudeVision::analisarFoto($caminhoArquivo, $mimeType, $nomeItem, $criterios),
                    default => throw new VisionAnalysisException("Provedor desconhecido: \"{$provedor}\""),
                };
            } catch (VisionAnalysisException $e) {
                error_log("VisionAnalyzer: provedor \"{$provedor}\" falhou, tentando o próximo: " . $e->getMessage());
                $erros[] = "{$provedor}: " . $e->getMessage();
            }
        }

        throw new VisionAnalysisException('Todos os provedores de IA falharam — ' . implode(' | ', $erros));
    }
}
