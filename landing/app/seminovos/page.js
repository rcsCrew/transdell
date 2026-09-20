import VehicleInquiryForm from "../../components/VehicleInquiryForm";

export const metadata = {
  title: "Seminovos",
  description:
    "Caminhões seminovos da própria frota Rotalog: inspeção técnica completa, manutenção preventiva e documentação em dia.",
};

export default function SeminovosPage() {
  return (
    <>
      <section className="page-hero">
        <svg className="decor" viewBox="0 0 1440 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
          <circle cx="200" cy="130" r="5" fill="var(--orange)"></circle>
          <circle cx="1240" cy="250" r="5" fill="var(--orange)"></circle>
          <path d="M200 130C480 230 740 70 1020 230S1200 290 1240 250" fill="none" stroke="#4A5D82" strokeWidth="1.4" strokeDasharray="2 8" strokeLinecap="round"></path>
        </svg>
        <div className="page-hero-inner">
          <div className="eyebrow">Seminovos</div>
          <h1>Caminhões de qualidade para sua frota</h1>
          <p>Veículos da nossa própria frota, com inspeção técnica rigorosa e manutenção em dia — 15 anos de experiência garantindo cada quilômetro rodado.</p>
          <div className="hero-cta" style={{ justifyContent: "center" }}>
            <a href="#veiculos" className="btn btn-primary">Ver Veículos Disponíveis <svg width="16" height="16" style={{ transform: "rotate(90deg)" }}><use href="#i-chevron"></use></svg></a>
          </div>
        </div>
      </section>

      <section className="section">
        <div className="container">
          <div className="section-head" style={{ maxWidth: 640 }}>
            <div className="eyebrow">Por que comprar seminovos da Rotalog</div>
            <h2>Qualidade e confiança em cada caminhão</h2>
          </div>
          <p style={{ maxWidth: "68ch", fontSize: 15.5, color: "var(--ink-soft)", lineHeight: 1.7 }}>Todos os nossos veículos passam por rigorosa inspeção técnica e manutenção preventiva, garantindo segurança e economia para sua operação. Com 15 anos de experiência no mercado de transportes, conhecemos cada detalhe dos nossos veículos e oferecemos garantia e suporte completo.</p>
          <div className="highlight-row">
            <div className="highlight-item">
              <div className="icon-box"><svg width="22" height="22"><use href="#i-clock"></use></svg></div>
              <div><h4>Manutenção Preventiva</h4><p>Todos os veículos com manutenção em dia</p></div>
            </div>
            <div className="highlight-item">
              <div className="icon-box"><svg width="22" height="22"><use href="#i-shield"></use></svg></div>
              <div><h4>Garantia Estendida</h4><p>Proteção adicional para sua tranquilidade</p></div>
            </div>
            <div className="highlight-item">
              <div className="icon-box"><svg width="22" height="22"><use href="#i-tag"></use></svg></div>
              <div><h4>Melhor Custo-Benefício</h4><p>Economia significativa sem perder qualidade</p></div>
            </div>
          </div>
        </div>
      </section>

      <section className="section section-tight" id="veiculos" style={{ background: "var(--paper-raised)", borderTop: "1px solid var(--line)", borderBottom: "1px solid var(--line)" }}>
        <div className="container">
          <div className="section-head" style={{ marginBottom: 32 }}>
            <div className="eyebrow">Estoque atual</div>
            <h2>Veículos disponíveis</h2>
          </div>
          <div className="vehicle-grid">
            <div className="vehicle-card">
              <div className="vehicle-media"><span className="vehicle-badge">Disponível</span><svg width="64" height="64"><use href="#i-truck"></use></svg></div>
              <div className="vehicle-body">
                <h3>Mercedes-Benz 710</h3>
                <div className="vehicle-meta"><span>Ano 2020</span><span>180.000 km</span></div>
                <div className="spec-tags"><span>Diesel</span><span>Manual</span><span>Ar Condicionado</span><span>Direção Hidráulica</span><span>Freio ABS</span></div>
                <div className="vehicle-price-row"><div><div className="price-label">Preço</div><div className="price-value">R$ 85.000</div></div></div>
                <div className="vehicle-actions">
                  <a href="/contato" className="btn btn-primary">Solicitar Informações</a>
                  <a href="#" className="btn btn-secondary">Ver Detalhes</a>
                </div>
              </div>
            </div>
            <div className="vehicle-card">
              <div className="vehicle-media"><span className="vehicle-badge">Disponível</span><svg width="64" height="64"><use href="#i-truck"></use></svg></div>
              <div className="vehicle-body">
                <h3>Volkswagen Delivery</h3>
                <div className="vehicle-meta"><span>Ano 2019</span><span>220.000 km</span></div>
                <div className="spec-tags"><span>Diesel</span><span>Manual</span><span>Carroceria Baú</span><span>Direção Hidráulica</span><span>Freio ABS</span></div>
                <div className="vehicle-price-row"><div><div className="price-label">Preço</div><div className="price-value">R$ 75.000</div></div></div>
                <div className="vehicle-actions">
                  <a href="/contato" className="btn btn-primary">Solicitar Informações</a>
                  <a href="#" className="btn btn-secondary">Ver Detalhes</a>
                </div>
              </div>
            </div>
            <div className="vehicle-card">
              <div className="vehicle-media"><span className="vehicle-badge">Disponível</span><svg width="64" height="64"><use href="#i-truck"></use></svg></div>
              <div className="vehicle-body">
                <h3>Scania R420</h3>
                <div className="vehicle-meta"><span>Ano 2018</span><span>280.000 km</span></div>
                <div className="spec-tags"><span>Diesel</span><span>Manual</span><span>Grade Baixa</span><span>Ar Condicionado</span><span>Freio ABS</span></div>
                <div className="vehicle-price-row"><div><div className="price-label">Preço</div><div className="price-value">R$ 120.000</div></div></div>
                <div className="vehicle-actions">
                  <a href="/contato" className="btn btn-primary">Solicitar Informações</a>
                  <a href="#" className="btn btn-secondary">Ver Detalhes</a>
                </div>
              </div>
            </div>
            <div className="vehicle-card">
              <div className="vehicle-media"><span className="vehicle-badge">Disponível</span><svg width="64" height="64"><use href="#i-truck"></use></svg></div>
              <div className="vehicle-body">
                <h3>Mercedes-Benz Actros</h3>
                <div className="vehicle-meta"><span>Ano 2017</span><span>350.000 km</span></div>
                <div className="spec-tags"><span>Diesel</span><span>Manual</span><span>Carreta</span><span>Ar Condicionado</span><span>Freio ABS</span></div>
                <div className="vehicle-price-row"><div><div className="price-label">Preço</div><div className="price-value">R$ 180.000</div></div></div>
                <div className="vehicle-actions">
                  <a href="/contato" className="btn btn-primary">Solicitar Informações</a>
                  <a href="#" className="btn btn-secondary">Ver Detalhes</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="section">
        <div className="container">
          <div className="section-head">
            <div className="eyebrow">Vantagens</div>
            <h2>Comprar conosco é mais tranquilo</h2>
          </div>
          <div className="benefits-grid">
            <div className="benefit-card">
              <div className="icon-box"><svg width="22" height="22"><use href="#i-check"></use></svg></div>
              <h3>Inspeção Completa</h3>
              <p>Todos os veículos passam por inspeção técnica rigorosa antes da venda, garantindo qualidade e segurança.</p>
            </div>
            <div className="benefit-card">
              <div className="icon-box"><svg width="22" height="22"><use href="#i-doc"></use></svg></div>
              <h3>Documentação em Dia</h3>
              <p>Documentação completa e regularizada, incluindo IPVA, licenciamento e multas quitadas.</p>
            </div>
            <div className="benefit-card">
              <div className="icon-box"><svg width="22" height="22"><use href="#i-shield"></use></svg></div>
              <h3>Manutenção Garantida</h3>
              <p>Histórico completo de manutenções e garantia estendida para sua tranquilidade.</p>
            </div>
          </div>
        </div>
      </section>

      <section className="section section-tight" style={{ background: "var(--paper-raised)", borderTop: "1px solid var(--line)" }}>
        <div className="container">
          <div className="contact-layout">
            <div>
              <div className="eyebrow">Fale conosco</div>
              <h2 style={{ fontSize: "clamp(24px,3vw,32px)", fontWeight: 700 }}>Interessado em nossos veículos?</h2>
              <p style={{ marginTop: 14, color: "var(--ink-soft)", fontSize: 15, lineHeight: 1.65 }}>Entre em contato para mais informações e agendamento de visita.</p>
              <div style={{ display: "flex", flexDirection: "column", gap: 14, marginTop: 24 }}>
                <div className="unit-line mono" style={{ marginTop: 0 }}><svg width="15" height="15"><use href="#i-phone"></use></svg>0800 000 0000 · (42) 9 0000-0000</div>
                <div className="unit-line" style={{ marginTop: 0 }}><svg width="15" height="15"><use href="#i-mail"></use></svg>adm@rotalog.com.br</div>
              </div>
            </div>
            <div className="form-card">
              <h2 style={{ fontSize: 20, marginBottom: 20 }}>Solicitar Informações</h2>
              <VehicleInquiryForm />
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
