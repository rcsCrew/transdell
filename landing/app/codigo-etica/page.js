export const metadata = {
  title: "Código de Ética",
  description:
    "Os princípios de segurança, transparência e respeito que guiam a relação da Rotalog com clientes, parceiros e colaboradores.",
};

export default function CodigoEticaPage() {
  return (
    <>
      <section className="page-hero" style={{ paddingBottom: 56 }}>
        <div className="page-hero-inner">
          <div className="eyebrow">Governança</div>
          <h1 style={{ fontSize: "clamp(28px,4vw,40px)" }}>Código de Ética</h1>
          <p>Os princípios que guiam cada decisão, cada entrega e cada relação da Rotalog.</p>
        </div>
      </section>

      <section className="section">
        <div className="container">
          <div className="callout-note">
            Este código reúne os princípios já declarados publicamente pela Rotalog (segurança, pontualidade, confiança e inovação). Antes da publicação oficial, recomendamos que a diretoria revise e complemente com processos formais de apuração e canal de denúncias.
          </div>

          <div className="legal-layout">
            <aside className="legal-toc">
              <h3>Nesta página</h3>
              <ol>
                <li><a href="#compromisso">Nosso compromisso</a></li>
                <li><a href="#principios">Princípios que nos guiam</a></li>
                <li><a href="#clientes">Clientes e parceiros</a></li>
                <li><a href="#colaboradores">Colaboradores</a></li>
                <li><a href="#seguranca-ambiente">Segurança e meio ambiente</a></li>
                <li><a href="#canal">Canal de ética</a></li>
              </ol>
            </aside>

            <div>
              <div className="legal-section" id="compromisso">
                <h2>1. Nosso compromisso</h2>
                <p>Desde os primeiros quilômetros rodados, a Rotalog construiu sua reputação sobre confiança, dedicação e compromisso. Este código formaliza os princípios que já orientam nossas operações diárias — para clientes, parceiros, colaboradores e comunidades onde atuamos.</p>
              </div>

              <div className="legal-section" id="principios">
                <h2>2. Princípios que nos guiam</h2>
                <div className="pillars-grid" style={{ marginTop: 20 }}>
                  <div className="pillar-card">
                    <div className="icon-box" style={{ marginBottom: 14 }}><svg width="20" height="20"><use href="#i-shield"></use></svg></div>
                    <h3>Segurança</h3>
                    <p>Priorizamos a integridade das cargas e das pessoas em toda operação, sem exceções.</p>
                  </div>
                  <div className="pillar-card">
                    <div className="icon-box" style={{ marginBottom: 14 }}><svg width="20" height="20"><use href="#i-badge"></use></svg></div>
                    <h3>Transparência</h3>
                    <p>Comunicamos prazos, custos e imprevistos com clareza, mesmo quando a notícia não é boa.</p>
                  </div>
                  <div className="pillar-card">
                    <div className="icon-box" style={{ marginBottom: 14 }}><svg width="20" height="20"><use href="#i-check"></use></svg></div>
                    <h3>Respeito</h3>
                    <p>Tratamos clientes, parceiros e colaboradores com ética e consideração em toda interação.</p>
                  </div>
                </div>
              </div>

              <div className="legal-section" id="clientes">
                <h2>3. Relação com clientes e parceiros</h2>
                <ul>
                  <li>Cumprimos prazos e compromissos assumidos, e avisamos com antecedência quando algo pode mudar</li>
                  <li>Não aceitamos vantagens indevidas de fornecedores ou parceiros em troca de tratamento preferencial</li>
                  <li>Protegemos as informações comerciais e os dados pessoais compartilhados conosco</li>
                </ul>
              </div>

              <div className="legal-section" id="colaboradores">
                <h2>4. Relação com colaboradores</h2>
                <ul>
                  <li>Oferecemos oportunidades de crescimento baseadas em mérito e resultado, não em favoritismo</li>
                  <li>Não toleramos discriminação, assédio ou qualquer forma de desrespeito no ambiente de trabalho</li>
                  <li>Garantimos canais para que qualquer colaborador possa expor dúvidas ou preocupações sem retaliação</li>
                </ul>
              </div>

              <div className="legal-section" id="seguranca-ambiente">
                <h2>5. Segurança e meio ambiente</h2>
                <p>Mantemos monitoramento contínuo das operações de transporte e protocolos rigorosos de segurança para cargas e motoristas. Buscamos continuamente reduzir o impacto ambiental da nossa operação, da manutenção da frota às rotas planejadas.</p>
              </div>

              <div className="legal-section" id="canal">
                <h2>6. Canal de ética</h2>
                <p>Dúvidas, sugestões ou preocupações sobre a conduta da empresa podem ser encaminhadas para <a href="mailto:adm@rotalog.com.br">adm@rotalog.com.br</a>. [Definir um canal dedicado e, se possível, anônimo, para relatos de violações a este código.]</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
