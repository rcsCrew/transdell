-- Migração: troca o tipo_analise fixo (só 'generico'/'pneu') por um campo de
-- texto livre onde o gestor escreve os critérios de análise de cada item.
ALTER TABLE itens_padrao ADD COLUMN criterios_analise TEXT NULL AFTER ativo;
ALTER TABLE checklist_itens ADD COLUMN criterios_analise TEXT NULL AFTER item_nome;

UPDATE itens_padrao SET criterios_analise =
  'Avalie o estado físico do pneu como um borracheiro experiente, olhando:\n- Profundidade dos sulcos da banda de rodagem (quanto mais raso, pior).\n- Desgaste irregular (um lado mais gasto que o outro, calvície no centro ou nas bordas).\n- Indicador de desgaste (TWI) aparente ou visivelmente no limite/apagado.\n- Cortes, bolhas, exposição de lona/cinta de aço, rachaduras no flanco.'
  WHERE chave = 'pneu';

ALTER TABLE itens_padrao DROP COLUMN tipo_analise;
ALTER TABLE checklist_itens DROP COLUMN tipo_analise;
