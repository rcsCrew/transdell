export const metadata = {
  title: "Sobre a Rotalog",
  description:
    "Conheça a história, os valores e os diferenciais da Rotalog Transportes, transportadora rodoviária de cargas sediada em Ponta Grossa/PR.",
};

export default function SobrePage() {
  return (
    <>
      <section className="page-hero">
        <svg className="decor" viewBox="0 0 1440 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
          <circle cx="180" cy="120" r="5" fill="var(--orange)"></circle>
          <circle cx="1260" cy="260" r="5" fill="var(--orange)"></circle>
          <path d="M180 120C460 220 720 60 1000 220S1220 300 1260 260" fill="none" stroke="#4A5D82" strokeWidth="1.4" strokeDasharray="2 8" strokeLinecap="round"></path>
        </svg>
        <div className="page-hero-inner">
          <div className="eyebrow">Sobre a Rotalog</div>
          <h1>Excelência em transporte de cargas</h1>
          <p>Confiança, dedicação e compromisso em cada quilômetro rodado desde o coração do Paraná.</p>
        </div>
      </section>

      <section className="section">
        <div className="container" style={{ display: "grid", gridTemplateColumns: "1fr", justifyItems: "center" }}>
          <div className="prose" style={{ width: "100%" }}>
            <div className="eyebrow">Nossa história</div>
            <p>A Rotalog nasceu no coração do Paraná a partir de um objetivo simples: oferecer soluções logísticas eficientes, seguras e personalizadas. Desde os primeiros quilômetros rodados, a empresa se destacou pela seriedade no atendimento e pelo cuidado em cada entrega, conquistando a confiança de parceiros que buscavam mais do que transporte — buscavam tranquilidade.</p>
            <p>Com o tempo, a frota foi renovada, os processos foram aperfeiçoados e a tecnologia passou a fazer parte do dia a dia da operação. Mesmo assim, o relacionamento humano e a atenção aos detalhes nunca saíram de cena.</p>
            <p>Hoje, a Rotalog se consolida como parceira estratégica de empresas de diversos setores, reconhecida pela pontualidade, responsabilidade e flexibilidade — sempre guiada pelos mesmos valores que marcaram o primeiro dia de operação.</p>
            <blockquote className="pull-quote">&quot;Cada entrega é uma missão, cada cliente é um parceiro.&quot;</blockquote>
          </div>
        </div>
      </section>

      <section className="section section-tight" style={{ background: "var(--paper-raised)", borderTop: "1px solid var(--line)", borderBottom: "1px solid var(--line)" }}>
        <div className="container">
          <div className="section-head" style={{ marginBottom: 32 }}>
            <div className="eyebrow">Como trabalhamos</div>
            <h2>Três pilares em toda operação</h2>
          </div>
          <div className="pillars-grid">
            <div className="pillar-card">
              <div className="icon-box" style={{ marginBottom: 16 }}><svg width="22" height="22"><use href="#i-shield"></use></svg></div>
              <h3>Segurança</h3>
              <p>Prioridade máxima em todas as operações, com monitoramento 24h e protocolos rigorosos.</p>
            </div>
            <div className="pillar-card">
              <div className="icon-box" style={{ marginBottom: 16 }}><svg width="22" height="22"><use href="#i-clock"></use></svg></div>
              <h3>Eficiência</h3>
              <p>Planejamento logístico detalhado, pontualidade e soluções sob medida.</p>
            </div>
            <div className="pillar-card">
              <div className="icon-box" style={{ marginBottom: 16 }}><svg width="22" height="22"><use href="#i-check"></use></svg></div>
              <h3>Compromisso</h3>
              <p>Transparência, ética e respeito em todas as relações.</p>
            </div>
          </div>
        </div>
      </section>

      <section className="section">
        <div className="container">
          <div className="section-head">
            <div className="eyebrow">Nossos valores</div>
            <h2>O que guia cada decisão</h2>
          </div>
          <div className="values-grid">
            <div className="value-card">
              <div className="icon-box"><svg width="20" height="20"><use href="#i-shield"></use></svg></div>
              <h3>Segurança</h3>
              <p>Integridade das cargas e das pessoas em toda operação.</p>
            </div>
            <div className="value-card">
              <div className="icon-box"><svg width="20" height="20"><use href="#i-clock"></use></svg></div>
              <h3>Pontualidade</h3>
              <p>Comprometimento com prazos e entregas no tempo acordado.</p>
            </div>
            <div className="value-card">
              <div className="icon-box"><svg width="20" height="20"><use href="#i-badge"></use></svg></div>
              <h3>Confiança</h3>
              <p>Relacionamentos duradouros baseados em transparência.</p>
            </div>
            <div className="value-card">
              <div className="icon-box"><svg width="20" height="20"><use href="#i-bulb"></use></svg></div>
              <h3>Inovação</h3>
              <p>Busca constante por melhorias e novas tecnologias.</p>
            </div>
          </div>
        </div>
      </section>

      <section className="diff-section">
        <div className="container" style={{ maxWidth: "var(--container-w)", margin: "0 auto" }}>
          <div className="section-head" style={{ maxWidth: 640 }}>
            <div className="eyebrow">Nossos diferenciais</div>
            <h2 style={{ color: "#fff" }}>Estrutura pensada para o seu negócio</h2>
          </div>
          <div className="values-grid">
            <div className="value-card" style={{ background: "var(--navy-2)", borderColor: "var(--navy-line)" }}>
              <div className="icon-box" style={{ background: "rgba(234,101,22,.16)" }}><svg width="20" height="20"><use href="#i-pin"></use></svg></div>
              <h3 style={{ color: "#fff" }}>Cobertura Nacional</h3>
              <p style={{ color: "#8C99B4" }}>Atendimento em todo o território brasileiro com eficiência e rapidez.</p>
            </div>
            <div className="value-card" style={{ background: "var(--navy-2)", borderColor: "var(--navy-line)" }}>
              <div className="icon-box" style={{ background: "rgba(234,101,22,.16)" }}><svg width="20" height="20"><use href="#i-clock"></use></svg></div>
              <h3 style={{ color: "#fff" }}>Suporte 24/7</h3>
              <p style={{ color: "#8C99B4" }}>Assistência contínua para garantir tranquilidade em todas as operações.</p>
            </div>
            <div className="value-card" style={{ background: "var(--navy-2)", borderColor: "var(--navy-line)" }}>
              <div className="icon-box" style={{ background: "rgba(234,101,22,.16)" }}><svg width="20" height="20"><use href="#i-doc"></use></svg></div>
              <h3 style={{ color: "#fff" }}>Documentação Completa</h3>
              <p style={{ color: "#8C99B4" }}>Gestão eficiente de toda a documentação necessária para o transporte.</p>
            </div>
            <div className="value-card" style={{ background: "var(--navy-2)", borderColor: "var(--navy-line)" }}>
              <div className="icon-box" style={{ background: "rgba(234,101,22,.16)" }}><svg width="20" height="20"><use href="#i-badge"></use></svg></div>
              <h3 style={{ color: "#fff" }}>Gestão de Qualidade</h3>
              <p style={{ color: "#8C99B4" }}>Processos otimizados para garantir a excelência dos serviços.</p>
            </div>
          </div>
        </div>
      </section>

      <section className="cta-banner">
        <div className="inner">
          <h2>Transportar sonhos, construir confiança, superar limites.</h2>
          <p>Esse é o caminho da Rotalog — e pode ser o caminho da sua carga também.</p>
          <a href="/contato" className="btn btn-white">Fale com a Rotalog</a>
        </div>
      </section>
    </>
  );
}
