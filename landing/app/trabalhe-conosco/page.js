import ApplicationForm from "../../components/ApplicationForm";

export const metadata = {
  title: "Trabalhe Conosco",
  description: "Faça parte da Rotalog: conheça nosso processo seletivo, benefícios e envie seu currículo.",
};

export default function TrabalheConoscoPage() {
  return (
    <>
      <section className="page-hero">
        <svg className="decor" viewBox="0 0 1440 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
          <circle cx="200" cy="130" r="5" fill="var(--orange)"></circle>
          <circle cx="1240" cy="250" r="5" fill="var(--orange)"></circle>
          <path d="M200 130C480 230 740 70 1020 230S1200 290 1240 250" fill="none" stroke="#4A5D82" strokeWidth="1.4" strokeDasharray="2 8" strokeLinecap="round"></path>
        </svg>
        <div className="page-hero-inner">
          <div className="eyebrow">Trabalhe conosco</div>
          <h1>Faça parte de uma equipe que não para de crescer</h1>
          <p>Carreira com propósito, crescimento real e uma cultura baseada em transparência e colaboração.</p>
          <div className="hero-cta" style={{ justifyContent: "center" }}>
            <a href="#candidatura" className="btn btn-primary">Ver Vagas Disponíveis</a>
          </div>
        </div>
      </section>

      <section className="section">
        <div className="container">
          <div className="section-head" style={{ maxWidth: 640 }}>
            <div className="eyebrow">Como funciona</div>
            <h2>Sua jornada na Rotalog</h2>
          </div>
          <div className="journey">
            <div className="journey-step">
              <div className="step-num">01</div>
              <div>
                <div className="step-title">Primeiro Contato</div>
                <p className="step-desc">Envie seu currículo e conheça nossa cultura empresarial.</p>
                <div className="step-tags"><span>Análise de perfil</span><span>Conheça nossa cultura</span><span>Primeira conversa</span></div>
              </div>
              <div className="step-duration">1–2 dias</div>
            </div>
            <div className="journey-step">
              <div className="step-num">02</div>
              <div>
                <div className="step-title">Processo Seletivo</div>
                <p className="step-desc">Entrevistas e avaliações personalizadas para encontrar o melhor match.</p>
                <div className="step-tags"><span>Entrevista RH</span><span>Avaliação técnica</span><span>Teste prático</span></div>
              </div>
              <div className="step-duration">3–5 dias</div>
            </div>
            <div className="journey-step">
              <div className="step-num">03</div>
              <div>
                <div className="step-title">Onboarding</div>
                <p className="step-desc">Treinamento completo e integração à nossa equipe.</p>
                <div className="step-tags"><span>Treinamento inicial</span><span>Conheça a equipe</span><span>Processos da empresa</span></div>
              </div>
              <div className="step-duration">1–2 semanas</div>
            </div>
            <div className="journey-step">
              <div className="step-num">04</div>
              <div>
                <div className="step-title">Crescimento</div>
                <p className="step-desc">Desenvolvimento contínuo e oportunidades de promoção.</p>
                <div className="step-tags"><span>Mentoria</span><span>Treinamentos</span><span>Promoções</span></div>
              </div>
              <div className="step-duration">Contínuo</div>
            </div>
          </div>
        </div>
      </section>

      <section className="section section-tight" style={{ background: "var(--paper-raised)", borderTop: "1px solid var(--line)", borderBottom: "1px solid var(--line)" }}>
        <div className="container">
          <div className="section-head" style={{ maxWidth: 680 }}>
            <div className="eyebrow">Por que trabalhar na Rotalog</div>
            <h2>Um emprego não, uma carreira com propósito</h2>
          </div>
          <p style={{ maxWidth: "68ch", fontSize: 15.5, color: "var(--ink-soft)", lineHeight: 1.7 }}>Somos uma empresa que valoriza pessoas, inovação e resultados. Nossa cultura é baseada na transparência, colaboração e excelência, onde cada colaborador tem voz ativa e oportunidades reais de desenvolvimento. Com 15 anos de experiência no mercado de transportes, construímos uma reputação sólida baseada na confiança dos nossos clientes e no compromisso da nossa equipe.</p>
          <div className="highlight-row">
            <div className="highlight-item">
              <div className="icon-box"><svg width="22" height="22"><use href="#i-badge"></use></svg></div>
              <div><h4>Crescimento Rápido</h4><p>Promoções baseadas em mérito e resultados</p></div>
            </div>
            <div className="highlight-item">
              <div className="icon-box"><svg width="22" height="22"><use href="#i-check"></use></svg></div>
              <div><h4>Equipe Incrível</h4><p>Profissionais experientes e apaixonados</p></div>
            </div>
            <div className="highlight-item">
              <div className="icon-box"><svg width="22" height="22"><use href="#i-route"></use></svg></div>
              <div><h4>Mercado em Expansão</h4><p>Setor logístico em constante crescimento</p></div>
            </div>
          </div>
        </div>
      </section>

      <section className="section">
        <div className="container">
          <div className="section-head">
            <div className="eyebrow">Benefícios</div>
            <h2>Benefícios que fazem a diferença</h2>
          </div>
          <div className="benefits-grid">
            <div className="benefit-card">
              <h3>Plano de Saúde</h3>
              <p>Cobertura completa para você e sua família com as melhores operadoras do mercado. Inclui consultas, exames e internações.</p>
              <div className="benefit-tags"><span>Sem coparticipação</span><span>Cobertura nacional</span><span>Inclusão de dependentes</span></div>
            </div>
            <div className="benefit-card">
              <h3>Vale Refeição</h3>
              <p>Benefício para suas refeições diárias com valores que cobrem almoço e jantar.</p>
              <div className="benefit-tags"><span>Valor diário atrativo</span><span>Cartão refeição</span><span>Sem desconto</span></div>
            </div>
            <div className="benefit-card">
              <h3>Vale Transporte</h3>
              <p>Suporte completo para seu deslocamento diário até o trabalho.</p>
              <div className="benefit-tags"><span>100% pago pela empresa</span><span>Estacionamento gratuito</span></div>
            </div>
          </div>
        </div>
      </section>

      <section className="section" style={{ paddingTop: 0 }}>
        <div className="container">
          <div className="quote-block">
            <p>&quot;Na Rotalog, cada dia é uma nova oportunidade de aprender, crescer e fazer a diferença. Nossa equipe é nossa maior riqueza.&quot;</p>
            <cite>— Diretoria Rotalog</cite>
          </div>
        </div>
      </section>

      <section className="section section-tight" id="candidatura" style={{ background: "var(--paper-raised)", borderTop: "1px solid var(--line)" }}>
        <div className="container" style={{ display: "flex", justifyContent: "center" }}>
          <div className="form-card" style={{ maxWidth: 640, width: "100%" }}>
            <h2 style={{ fontSize: 22, marginBottom: 8 }}>Envie Seu Currículo</h2>
            <p>Preencha o formulário abaixo e nossa equipe entrará em contato com você.</p>
            <ApplicationForm />
          </div>
        </div>
      </section>
    </>
  );
}
