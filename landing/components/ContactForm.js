"use client";

export default function ContactForm() {
  return (
    <form onSubmit={(e) => e.preventDefault()}>
      <div className="form-field">
        <label htmlFor="nome">Nome completo *</label>
        <input id="nome" type="text" required />
      </div>
      <div className="form-row">
        <div className="form-field">
          <label htmlFor="email">E-mail *</label>
          <input id="email" type="email" required />
        </div>
        <div className="form-field">
          <label htmlFor="telefone">Telefone *</label>
          <input id="telefone" type="tel" required />
        </div>
      </div>
      <div className="form-field">
        <label htmlFor="empresa">Empresa</label>
        <input id="empresa" type="text" />
      </div>
      <div className="form-field">
        <label htmlFor="mensagem">Mensagem *</label>
        <textarea id="mensagem" required></textarea>
      </div>
      <button type="submit" className="btn btn-primary btn-block">Enviar Mensagem</button>
    </form>
  );
}
