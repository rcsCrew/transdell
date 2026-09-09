-- Sistema de Checklist de Frota
-- Execute este arquivo no MySQL do Laragon (HeidiSQL ou `mysql -u root`) antes de usar o sistema.

CREATE DATABASE IF NOT EXISTS sistema_do_paulo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_do_paulo;

CREATE TABLE usuarios_gestor (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  senha_hash VARCHAR(255) NOT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE motoristas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  pin CHAR(4) NOT NULL UNIQUE,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- criterios_analise é o que o gestor escreve pra "treinar" a IA nesse item
-- específico (ex: pontos de desgaste a procurar num pneu). Em branco/NULL =
-- a IA só dá uma avaliação genérica OK/Atenção/Crítico. Preenchido = a IA
-- usa esses critérios pra dar uma avaliação mais profunda (com classificação
-- Excelente/Bom/Regular/Crítico + estimativa de % de vida útil).
CREATE TABLE itens_padrao (
  id INT AUTO_INCREMENT PRIMARY KEY,
  chave VARCHAR(50) NOT NULL UNIQUE,
  nome VARCHAR(120) NOT NULL,
  ordem INT NOT NULL DEFAULT 0,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  criterios_analise TEXT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Veículos (cavalos) cadastrados pelo gestor. O motorista só consegue
-- iniciar um checklist com uma placa que exista aqui e esteja ativa —
-- sem vínculo fixo entre motorista e caminhão (qualquer motorista ativo
-- pode dirigir qualquer caminhão ativo).
CREATE TABLE caminhoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  placa VARCHAR(10) NOT NULL UNIQUE,
  modelo VARCHAR(120) NULL,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE checklists (
  id INT AUTO_INCREMENT PRIMARY KEY,
  motorista_id INT NOT NULL,
  placa VARCHAR(10) NOT NULL,
  km INT NULL,
  latitude DECIMAL(10,7) NULL,
  longitude DECIMAL(10,7) NULL,
  status ENUM('rascunho','enviado') NOT NULL DEFAULT 'rascunho',
  analise_concluida TINYINT(1) NOT NULL DEFAULT 0,
  total_ok INT NOT NULL DEFAULT 0,
  total_atencao INT NOT NULL DEFAULT 0,
  total_critico INT NOT NULL DEFAULT 0,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  enviado_em DATETIME NULL,
  FOREIGN KEY (motorista_id) REFERENCES motoristas(id),
  INDEX idx_placa (placa),
  INDEX idx_status (status)
) ENGINE=InnoDB;

-- Um item do checklist (ex: "Cintas" no checklist #42). O status aqui é o
-- agregado (o pior status entre todas as fotos desse item) — as fotos em si
-- ficam em checklist_fotos, porque agora um item pode ter várias.
CREATE TABLE checklist_itens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  checklist_id INT NOT NULL,
  item_chave VARCHAR(50) NOT NULL,
  item_nome VARCHAR(120) NOT NULL,
  criterios_analise TEXT NULL,
  status ENUM('ok','atencao','critico') NULL,
  resolvido TINYINT(1) NOT NULL DEFAULT 0,
  resolvido_em DATETIME NULL,
  resolvido_por INT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (checklist_id) REFERENCES checklists(id) ON DELETE CASCADE,
  FOREIGN KEY (resolvido_por) REFERENCES usuarios_gestor(id),
  UNIQUE KEY uniq_checklist_item (checklist_id, item_chave)
) ENGINE=InnoDB;

-- Cada foto tirada dentro de um item. Um item pode ter várias (ex: cinta
-- fotografada de 2 ângulos diferentes). O motorista só sobe a foto — quem
-- classifica é o worker em segundo plano (worker/processar_fotos.php).
-- status NULL = ainda não analisada. tentativas conta quantas vezes o
-- worker tentou; depois de MAX_TENTATIVAS falhas seguidas, marca
-- precisa_revisao_manual pro gestor classificar pelo painel.
CREATE TABLE checklist_fotos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  checklist_item_id INT NOT NULL,
  foto_path VARCHAR(255) NOT NULL,
  status ENUM('ok','atencao','critico') NULL,
  observacao_ia TEXT NULL,
  classificacao VARCHAR(30) NULL,
  vida_util_percentual TINYINT UNSIGNED NULL,
  tentativas INT NOT NULL DEFAULT 0,
  precisa_revisao_manual TINYINT(1) NOT NULL DEFAULT 0,
  erro_ia TEXT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (checklist_item_id) REFERENCES checklist_itens(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Contador de chamadas de IA por dia — trava de segurança contra custo
-- descontrolado (bug, loop, abuso). Cada análise soma 1 aqui; se passar do
-- LIMITE_IA_DIARIO do .env, o sistema pausa novas análises até o dia
-- seguinte (não conta como "tentativa perdida" da foto, só adia).
CREATE TABLE limite_ia_diario (
  dia DATE PRIMARY KEY,
  chamadas INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE alertas_enviados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  checklist_id INT NOT NULL,
  canal VARCHAR(20) NOT NULL,
  destino VARCHAR(150) NULL,
  sucesso TINYINT(1) NOT NULL DEFAULT 0,
  erro TEXT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (checklist_id) REFERENCES checklists(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Itens padrão do checklist (o gestor pode editar/adicionar/excluir depois pelo painel)
INSERT INTO itens_padrao (chave, nome, ordem, criterios_analise) VALUES
  ('cinta', 'Cintas de amarração', 1, NULL),
  ('catraca', 'Catracas', 2, NULL),
  ('presilha', 'Presilhas / lonas', 3, NULL),
  ('pneu', 'Pneus', 4,
    'Avalie o estado físico do pneu como um borracheiro experiente, olhando:\n- Profundidade dos sulcos da banda de rodagem (quanto mais raso, pior).\n- Desgaste irregular (um lado mais gasto que o outro, calvície no centro ou nas bordas).\n- Indicador de desgaste (TWI) aparente ou visivelmente no limite/apagado.\n- Cortes, bolhas, exposição de lona/cinta de aço, rachaduras no flanco.'),
  ('estepe', 'Estepe e triângulo', 5, NULL),
  ('quintaroda', 'Quinta roda / engate', 6, NULL);

-- Usuário gestor inicial: e-mail admin@frota.local / senha temporária "mudar123"
-- TROQUE a senha assim que entrar no painel (Meu perfil > alterar senha).
INSERT INTO usuarios_gestor (nome, email, senha_hash) VALUES
  ('Paulo', 'admin@frota.local', '$2y$10$T73cVVcEaHElwbPvQBsD9enPM64ml2u.7DAkgW.XFwdIr0IVRfu9K');
