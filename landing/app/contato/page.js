import ContactForm from "../../components/ContactForm";

export const metadata = {
  title: "Contato",
  description:
    "Fale com a Rotalog: telefone, e-mail, endereço da matriz em Ponta Grossa/PR e formulário de contato.",
};

export default function ContatoPage() {
  return (
    <>
      <section className="page-hero">
        <svg className="decor" viewBox="0 0 1440 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
          <circle cx="200" cy="130" r="5" fill="var(--orange)"></circle>
          <circle cx="1240" cy="250" r="5" fill="var(--orange)"></circle>
          <path d="M200 130C480 230 740 70 1020 230S1200 290 1240 250" fill="none" stroke="#4A5D82" strokeWidth="1.4" strokeDasharray="2 8" strokeLinecap="round"></path>
        </svg>
        <div className="page-hero-inner">
          <div className="eyebrow">Contato</div>
          <h1>Estamos aqui para atender você</h1>
          <p>Entre em contato pelos canais abaixo ou preencha o formulário. Respondemos em até 24 horas.</p>
        </div>
      </section>

      <section className="section">
        <div className="container">
          <div className="contact-layout">
            <div>
              <div className="section-head" style={{ marginBottom: 24 }}>
                <div className="eyebrow">Fale conosco</div>
                <h2>Canais diretos</h2>
              </div>
              <div className="channel-grid">
                <div className="channel-card">
                  <div className="icon-box"><svg width="20" height="20"><use href="#i-phone"></use></svg></div>
                  <h3>Telefone</h3>
                  <div className="value mono">0800 000 0000</div>
                  <div className="value mono">(42) 9 0000-0000</div>
                  <a href="tel:08000000000" className="card-link">Ligar Agora <svg width="14" height="14"><use href="#i-chevron"></use></svg></a>
                </div>
                <div className="channel-card">
                  <div className="icon-box"><svg width="20" height="20"><use href="#i-mail"></use></svg></div>
                  <h3>E-mail</h3>
                  <div className="value">adm@rotalog.com.br</div>
                  <div className="value">comercial@rotalog.com.br</div>
                  <a href="mailto:comercial@rotalog.com.br" className="card-link">Enviar E-mail <svg width="14" height="14"><use href="#i-chevron"></use></svg></a>
                </div>
                <div className="channel-card">
                  <div className="icon-box"><svg width="20" height="20"><use href="#i-pin"></use></svg></div>
                  <h3>Endereço</h3>
                  <div className="value">Av. Modelo, 100<br />Ponta Grossa/PR — CEP 00000-000</div>
                  <a href="#localizacao" className="card-link">Ver no Mapa <svg width="14" height="14"><use href="#i-chevron"></use></svg></a>
                </div>
                <div className="channel-card">
                  <div className="icon-box"><svg width="20" height="20"><use href="#i-clock"></use></svg></div>
                  <h3>Horário de Atendimento</h3>
                  <div className="value">Segunda a Sexta: 08h às 18h<br />Sábado: 08h às 12h</div>
                  <span className="badge-open"><span className="dot"></span>Aberto Agora</span>
                </div>
              </div>
            </div>

            <div className="form-card">
              <h2 style={{ fontSize: 22, marginBottom: 8 }}>Envie uma Mensagem</h2>
              <p>Preencha o formulário abaixo e entraremos em contato em até 24 horas.</p>
              <ContactForm />
            </div>
          </div>
        </div>
      </section>

      <section className="section section-tight" style={{ background: "var(--paper-raised)", borderTop: "1px solid var(--line)", borderBottom: "1px solid var(--line)" }}>
        <div className="container">
          <div className="section-head" style={{ marginBottom: 32 }}>
            <div className="eyebrow">Redes sociais</div>
            <h2>Siga a Rotalog</h2>
          </div>
          <div className="social-grid">
            <div className="social-card">
              <div className="icon-box"><svg width="20" height="20"><use href="#i-chat"></use></svg></div>
              <h3>WhatsApp</h3>
              <p>Fale diretamente conosco</p>
              <a href="#" className="card-link">Conversar <svg width="14" height="14"><use href="#i-chevron"></use></svg></a>
            </div>
            <div className="social-card">
              <div className="icon-box"><svg width="20" height="20"><use href="#i-insta"></use></svg></div>
              <h3>Instagram</h3>
              <p>@rotalog.oficial</p>
              <a href="#" className="card-link">Seguir <svg width="14" height="14"><use href="#i-chevron"></use></svg></a>
            </div>
            <div className="social-card">
              <div className="icon-box"><svg width="20" height="20"><use href="#i-linkedin"></use></svg></div>
              <h3>LinkedIn</h3>
              <p>Rotalog Transportes</p>
              <a href="#" className="card-link">Conectar <svg width="14" height="14"><use href="#i-chevron"></use></svg></a>
            </div>
          </div>
        </div>
      </section>

      <section className="section" id="localizacao">
        <div className="container">
          <div className="section-head">
            <div className="eyebrow">Nossa localização</div>
            <h2>Matriz — Ponta Grossa/PR</h2>
          </div>
          <div className="unit-card" style={{ maxWidth: 420 }}>
            <span className="tag matriz">Matriz</span>
            <h3>Ponta Grossa · PR</h3>
            <div className="unit-line"><svg width="15" height="15"><use href="#i-pin"></use></svg>Av. Modelo, 100 — CEP 00000-000</div>
            <div className="unit-line mono"><svg width="15" height="15"><use href="#i-phone"></use></svg>(42) 9 0000-0000</div>
            <div className="unit-line"><svg width="15" height="15"><use href="#i-mail"></use></svg>adm@rotalog.com.br</div>
            <a href="#" className="card-link">Como Chegar <svg width="14" height="14"><use href="#i-chevron"></use></svg></a>
          </div>
        </div>
      </section>
    </>
  );
}
