export default function HomePage() {
  return (
    <>
      <section className="hero">
        <svg className="decor" viewBox="0 0 1440 620" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
          <circle cx="140" cy="140" r="5" fill="var(--orange)"></circle>
          <circle cx="1310" cy="430" r="5" fill="var(--orange)"></circle>
          <path d="M140 140C420 260 700 60 980 260S1250 420 1310 430" fill="none" stroke="#4A5D82" strokeWidth="1.4" strokeDasharray="2 8" strokeLinecap="round"></path>
          <path d="M-40 420C260 340 520 520 860 380S1260 200 1500 260" fill="none" stroke="#4A5D82" strokeWidth="1.2" strokeDasharray="2 8" strokeLinecap="round"></path>
        </svg>
        <div className="hero-grid">
          <div className="hero-copy">
            <div className="eyebrow">Transporte rodoviário de cargas</div>
            <h1>Sua carga chega certa, no prazo certo, em qualquer lugar do Brasil.</h1>
            <p>Da matriz em Ponta Grossa às filiais em Guarulhos e Camaçari: distribuição urbana, armazenagem, transferência interestadual e operação de contêiner, com o mesmo cuidado de sempre.</p>
            <div className="hero-cta">
              <a href="#cotacao" className="btn btn-primary">Solicitar Cotação <svg width="16" height="16"><use href="#i-arrow"></use></svg></a>
              <a href="#rastreio" className="btn btn-outline">Rastrear Carga</a>
            </div>
          </div>
          <div className="hero-widget" id="rastreio">
            <div className="tabs">
              <div className="tab active"><svg width="16" height="16" style={{ color: "var(--orange)" }}><use href="#i-search"></use></svg>Rastrear Carga</div>
              <div className="tab">Cotação de Frete</div>
            </div>
            <div className="widget-panel">
              <label htmlFor="nf">Número da NF ou CT-e</label>
              <input id="nf" type="text" placeholder="Ex: 000123456" />
              <button className="btn btn-primary btn-block">Rastrear Agora</button>
              <div className="widget-footnote"><svg width="14" height="14"><use href="#i-shield"></use></svg>Rastreamento disponível 24 horas.</div>
            </div>
          </div>
        </div>

        <div className="stats-wrap">
          <div className="stats-strip">
            <div className="stat-cell"><div className="stat-value">15 anos</div><div className="stat-label">de mercado</div></div>
            <div className="stat-cell"><div className="stat-value">3 unidades</div><div className="stat-label">PR · SP · BA</div></div>
            <div className="stat-cell"><div className="stat-value placeholder">[X] veículos</div><div className="stat-label">na frota própria</div></div>
            <div className="stat-cell"><div className="stat-value placeholder">[X] entregas</div><div className="stat-label">por mês, em todo o Brasil</div></div>
          </div>
          <p className="stats-note">* números de frota e volume operacional: a confirmar com a operação antes de publicar</p>
        </div>
      </section>

      <section className="section" id="servicos" style={{ paddingTop: 170 }}>
        <div className="container">
          <div className="section-head">
            <div className="eyebrow">O que fazemos</div>
            <h2>Soluções logísticas para cada etapa da sua cadeia</h2>
          </div>
          <div className="services-grid">
            <div className="service-card">
              <div className="icon-box"><svg width="24" height="24"><use href="#i-truck"></use></svg></div>
              <h3>Distribuição Urbana</h3>
              <p>Entrega ágil dentro dos grandes centros urbanos.</p>
              <a href="#" className="card-link">Saiba mais <svg width="14" height="14"><use href="#i-chevron"></use></svg></a>
            </div>
            <div className="service-card">
              <div className="icon-box"><svg width="24" height="24"><use href="#i-warehouse"></use></svg></div>
              <h3>Armazenagem</h3>
              <p>Guarda segura de mercadorias entre etapas do transporte.</p>
              <a href="#" className="card-link">Saiba mais <svg width="14" height="14"><use href="#i-chevron"></use></svg></a>
            </div>
            <div className="service-card">
              <div className="icon-box"><svg width="24" height="24"><use href="#i-route"></use></svg></div>
              <h3>Transferência Brasil</h3>
              <p>Transporte interestadual apoiado pela matriz no Paraná.</p>
              <a href="#" className="card-link">Saiba mais <svg width="14" height="14"><use href="#i-chevron"></use></svg></a>
            </div>
            <div className="service-card">
              <div className="icon-box"><svg width="24" height="24"><use href="#i-container"></use></svg></div>
              <h3>Operação Container</h3>
              <p>Movimentação de cargas conteinerizadas de ponta a ponta.</p>
              <a href="#" className="card-link">Saiba mais <svg width="14" height="14"><use href="#i-chevron"></use></svg></a>
            </div>
          </div>
        </div>
      </section>

      <section className="diff-section" id="sobre">
        <div className="diff-grid">
          <div className="diff-visual">
            <div className="dark-card">
              <div className="icon-box"><svg width="22" height="22"><use href="#i-truck"></use></svg></div>
              <div>
                <h3>Frota Própria</h3>
                <p>Renovada e revisada continuamente</p>
              </div>
            </div>
            <div className="white-card">
              <div className="icon-box"><svg width="20" height="20"><use href="#i-tag"></use></svg></div>
              <h3>Seminovos à Venda</h3>
              <p>Caminhões da nossa própria frota, com inspeção completa</p>
            </div>
          </div>
          <div className="diff-copy">
            <div className="eyebrow">Por que a Rotalog</div>
            <h2>Confiança que a gente prova, não só promete</h2>
            <ul className="checklist">
              <li><span className="dot"><svg width="14" height="14"><use href="#i-check"></use></svg></span><span>Segurança 24 horas em todas as operações e cargas</span></li>
              <li><span className="dot"><svg width="14" height="14"><use href="#i-check"></use></svg></span><span>Frota renovada — provamos vendendo nossos próprios seminovos</span></li>
              <li><span className="dot"><svg width="14" height="14"><use href="#i-check"></use></svg></span><span>Documentação completa em cada etapa do transporte</span></li>
              <li><span className="dot"><svg width="14" height="14"><use href="#i-check"></use></svg></span><span>Atendimento direto com quem decide, sem central de espera</span></li>
            </ul>
            <a href="/seminovos" className="diff-link">Conheça nossos seminovos <svg width="15" height="15"><use href="#i-arrow"></use></svg></a>
          </div>
        </div>
      </section>

      <section className="section" id="unidades">
        <div className="container">
          <div className="units-head">
            <div>
              <div className="eyebrow">Onde estamos</div>
              <h2 style={{ fontSize: "clamp(26px,3.4vw,36px)", fontWeight: 700, letterSpacing: "-.01em" }}>Da matriz no Paraná às filiais em expansão</h2>
            </div>
            <div className="search-box">
              <svg width="16" height="16"><use href="#i-search"></use></svg>
              <input type="text" placeholder="Busque sua cidade" />
            </div>
          </div>
          <div className="units-grid">
            <div className="unit-card">
              <span className="tag matriz">Matriz</span>
              <h3>Ponta Grossa · PR</h3>
              <div className="unit-line"><svg width="15" height="15"><use href="#i-pin"></use></svg>Av. Modelo, 100 — CEP 00000-000</div>
              <div className="unit-line mono"><svg width="15" height="15"><use href="#i-phone"></use></svg>(42) 9 0000-0000</div>
              <a href="#" className="card-link">Como chegar <svg width="14" height="14"><use href="#i-chevron"></use></svg></a>
            </div>
            <div className="unit-card">
              <span className="tag filial">Filial</span>
              <h3>Guarulhos · SP</h3>
              <div className="unit-line placeholder"><svg width="15" height="15"><use href="#i-pin"></use></svg>Endereço a confirmar</div>
              <div className="unit-line mono placeholder"><svg width="15" height="15"><use href="#i-phone"></use></svg>[telefone real da filial]</div>
              <a href="#" className="card-link">Como chegar <svg width="14" height="14"><use href="#i-chevron"></use></svg></a>
            </div>
            <div className="unit-card">
              <span className="tag filial">Filial</span>
              <h3>Camaçari · BA</h3>
              <div className="unit-line placeholder"><svg width="15" height="15"><use href="#i-pin"></use></svg>Endereço a confirmar</div>
              <div className="unit-line mono placeholder"><svg width="15" height="15"><use href="#i-phone"></use></svg>[telefone real da filial]</div>
              <a href="#" className="card-link">Como chegar <svg width="14" height="14"><use href="#i-chevron"></use></svg></a>
            </div>
          </div>
        </div>
      </section>

      <section className="compliance-bar">
        <div className="compliance-row">
          <div className="compliance-item"><svg width="19" height="19"><use href="#i-shield"></use></svg>Seguro de carga em todas as viagens</div>
          <div className="compliance-item"><svg width="19" height="19"><use href="#i-doc"></use></svg>Conforme a LGPD</div>
          <div className="compliance-item"><svg width="19" height="19"><use href="#i-badge"></use></svg>Gestão da qualidade</div>
          <div className="compliance-item"><svg width="19" height="19"><use href="#i-clock"></use></svg>Suporte 24 horas</div>
        </div>
      </section>

      <section className="cta-banner" id="cotacao">
        <div className="inner">
          <h2>Pronto para colocar sua carga no caminho certo?</h2>
          <p>Fale com nosso time comercial e receba uma cotação sob medida para sua operação.</p>
          <a href="/contato" className="btn btn-white">Solicitar Cotação Agora</a>
        </div>
      </section>
    </>
  );
}
