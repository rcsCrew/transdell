-- Migração: contenção anti-loop / anti-custo-descontrolado.
CREATE TABLE IF NOT EXISTS limite_ia_diario (
  dia DATE PRIMARY KEY,
  chamadas INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;
