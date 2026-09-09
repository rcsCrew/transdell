<?php
declare(strict_types=1);

/**
 * Monta o prompt certo pro item. Sem critérios (null/vazio) = avaliação
 * genérica OK/Atenção/Crítico. Com critérios (texto escrito pelo gestor no
 * painel) = avaliação profunda seguindo exatamente o que foi pedido, com
 * classificação de condição + estimativa de % de vida útil.
 */
function montar_prompt_analise_foto(string $nomeItem, ?string $criterios = null): string
{
    $criterios = trim((string)$criterios);
    return $criterios !== ''
        ? montar_prompt_personalizado($nomeItem, $criterios)
        : montar_prompt_generico($nomeItem);
}

function montar_prompt_generico(string $nomeItem): string
{
    return "Você é um inspetor de manutenção de frota de caminhões. Analise esta foto do item "
        . "\"{$nomeItem}\" de um caminhão de carga. Procure sinais de desgaste, rasgos, corrosão, folga, "
        . "quebra ou qualquer divergência que poderia causar problema durante uma viagem ou reclamação de "
        . "cliente.\n\n"
        . regra_foto_errada($nomeItem)
        . "Responda APENAS em JSON, sem markdown, sem texto fora do JSON, no formato exato: "
        . '{"status": "ok" ou "atencao" ou "critico", "observacao": "frase curta em português, no máximo 20 palavras, explicando o que foi visto"}';
}

function montar_prompt_personalizado(string $nomeItem, string $criterios): string
{
    return "Você é um inspetor especialista em manutenção de frota de caminhões, com foco específico no "
        . "item \"{$nomeItem}\". Analise esta foto com o máximo de detalhe e precisão possível, seguindo "
        . "exatamente estes critérios definidos pelo gestor da frota:\n\n{$criterios}\n\n"
        . "Depois de avaliar, classifique a condição do item em uma destas 4 categorias, sendo o mais "
        . "criterioso possível (não seja otimista demais — a segurança da viagem depende dessa avaliação):\n"
        . "- \"excelente\": praticamente sem sinais de desgaste/problema, ~85-100% de vida útil/condição restante.\n"
        . "- \"bom\": desgaste normal de uso, ainda seguro por bastante tempo, ~55-84%.\n"
        . "- \"regular\": desgaste avançado ou problema começando a aparecer, precisa ficar de olho, ~25-54%.\n"
        . "- \"critico\": problema sério, risco real, precisa de ação/troca/reparo antes da próxima viagem, ~0-24%.\n\n"
        . regra_foto_errada($nomeItem)
        . "Responda APENAS em JSON, sem markdown, sem texto fora do JSON, no formato exato: "
        . '{"status": "ok" ou "atencao" ou "critico", "classificacao": "excelente" ou "bom" ou "regular" ou "critico", '
        . '"vida_util_percentual": numero inteiro de 0 a 100 (sua melhor estimativa), '
        . '"observacao": "frase curta em português, no máximo 25 palavras, explicando exatamente o que foi visto, citando os critérios acima"}'
        . "\n\nMapeamento obrigatório entre classificacao e status: excelente ou bom -> \"ok\"; "
        . 'regular -> "atencao"; critico -> "critico".';
}

function regra_foto_errada(string $nomeItem): string
{
    return "REGRA IMPORTANTE: se a foto não mostrar claramente o item \"{$nomeItem}\" de um caminhão "
        . "(por exemplo: foto de outra coisa, tela de computador, foto borrada demais, ambiente errado, "
        . "ou qualquer imagem que não permita inspecionar o item de verdade), classifique SEMPRE como "
        . "\"critico\" (e, se for o caso, \"classificacao\": \"critico\") e explique na observação que a "
        . "foto não corresponde ao item e precisa ser refeita. Nunca classifique uma foto errada/não "
        . "correspondente como \"ok\" ou \"atencao\" — isso esconderia do gestor que o item nunca foi "
        . "realmente inspecionado.\n\n";
}

/**
 * Valida e normaliza o JSON que a IA devolveu, com fallback seguro (sempre
 * "critico" quando não dá pra confiar na resposta) e mapeamento consistente
 * entre classificacao e status quando o item tem critérios personalizados.
 *
 * @return array{status: string, observacao: string, classificacao: ?string, vida_util_percentual: ?int}
 */
function normalizar_resposta_ia($parsed): array
{
    $fallback = [
        'status' => 'critico',
        'observacao' => 'Não foi possível interpretar a resposta da IA automaticamente. Verifique a foto manualmente.',
        'classificacao' => null,
        'vida_util_percentual' => null,
    ];

    if (!is_array($parsed) || !isset($parsed['status'], $parsed['observacao'])) {
        return $fallback;
    }

    $status = in_array($parsed['status'], ['ok', 'atencao', 'critico'], true) ? $parsed['status'] : 'critico';

    $classificacoesValidas = ['excelente', 'bom', 'regular', 'critico'];
    $classificacao = isset($parsed['classificacao']) && in_array($parsed['classificacao'], $classificacoesValidas, true)
        ? $parsed['classificacao']
        : null;

    // Se veio classificação, o status precisa bater com ela (a IA às vezes
    // erra o mapeamento sozinha) — a classificação manda.
    if ($classificacao !== null) {
        $status = match ($classificacao) {
            'excelente', 'bom' => 'ok',
            'regular' => 'atencao',
            'critico' => 'critico',
        };
    }

    $vidaUtil = null;
    if (isset($parsed['vida_util_percentual']) && is_numeric($parsed['vida_util_percentual'])) {
        $vidaUtil = max(0, min(100, (int)round((float)$parsed['vida_util_percentual'])));
    }

    return [
        'status' => $status,
        'observacao' => (string)$parsed['observacao'],
        'classificacao' => $classificacao,
        'vida_util_percentual' => $vidaUtil,
    ];
}
