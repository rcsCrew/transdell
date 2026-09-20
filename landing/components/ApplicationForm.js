"use client";

export default function ApplicationForm() {
  return (
    <form onSubmit={(e) => e.preventDefault()}>
      <div className="form-row">
        <div className="form-field"><label htmlFor="c-nome">Nome completo *</label><input id="c-nome" type="text" required /></div>
        <div className="form-field"><label htmlFor="c-cidade">Cidade *</label><input id="c-cidade" type="text" required /></div>
      </div>
      <div className="form-row">
        <div className="form-field"><label htmlFor="c-email">E-mail *</label><input id="c-email" type="email" required /></div>
        <div className="form-field"><label htmlFor="c-tel">Telefone *</label><input id="c-tel" type="tel" required /></div>
      </div>
      <div className="form-row">
        <div className="form-field">
          <label htmlFor="c-vaga">Vaga de interesse *</label>
          <select id="c-vaga" required>
            <option>Selecione uma vaga</option>
            <option>Motorista Rodoviário</option>
            <option>Operador Logístico</option>
            <option>Atendimento ao Cliente</option>
            <option>Outras Vagas</option>
          </select>
        </div>
        <div className="form-field">
          <label htmlFor="c-exp">Anos de experiência</label>
          <select id="c-exp">
            <option>Selecione</option>
            <option>Sem experiência</option>
            <option>1–2 anos</option>
            <option>3–5 anos</option>
            <option>5–10 anos</option>
            <option>Mais de 10 anos</option>
          </select>
        </div>
      </div>
      <div className="form-field">
        <label htmlFor="c-cv">Currículo (PDF) *</label>
        <div className="file-input"><svg width="16" height="16"><use href="#i-upload"></use></svg>Escolher arquivo — nenhum arquivo selecionado</div>
        <input id="c-cv" type="file" accept="application/pdf" style={{ display: "none" }} />
        <div className="hint">Formatos aceitos: PDF, DOC, DOCX (máx. 5MB)</div>
      </div>
      <div className="form-field"><label htmlFor="c-msg">Mensagem (opcional)</label><textarea id="c-msg"></textarea></div>
      <button type="submit" className="btn btn-primary btn-block">Enviar Candidatura</button>
    </form>
  );
}
