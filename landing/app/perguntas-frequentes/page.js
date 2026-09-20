export const metadata = {
  title: "Perguntas Frequentes",
  description:
    "Tire suas dúvidas sobre rastreamento, cotação, documentação de transporte, seminovos e vagas na Rotalog.",
};

export default function PerguntasFrequentesPage() {
  return (
    <>
      <section className="page-hero">
        <svg className="decor" viewBox="0 0 1440 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
          <circle cx="200" cy="130" r="5" fill="var(--orange)"></circle>
          <circle cx="1240" cy="250" r="5" fill="var(--orange)"></circle>
          <path d="M200 130C480 230 740 70 1020 230S1200 290 1240 250" fill="none" stroke="#4A5D82" strokeWidth="1.4" strokeDasharray="2 8" strokeLinecap="round"></path>
        </svg>
        <div className="page-hero-inner">
          <div className="eyebrow">Perguntas Frequentes</div>
          <h1>Tire suas dúvidas</h1>
          <p>Reunimos as perguntas mais comuns sobre transporte, documentação e seminovos. Não encontrou o que procurava? <a href="/contato" style={{ color: "var(--orange)" }}>Fale conosco</a>.</p>
        </div>
      </section>

      <section className="section">
        <div className="container">
          <div className="legal-section" style={{ marginBottom: 56 }}>
            <h2 style={{ fontSize: 20, marginBottom: 20 }}>Transporte e rastreamento</h2>
            <div className="faq-list">
              <details className="faq-item" open>
                <summary>Como funciona o rastreamento da minha carga? <span className="plus"><svg width="12" height="12"><use href="#i-plus"></use></svg></span></summary>
                <div className="faq-answer">Informe o número da nota fiscal ou do CT-e no campo de rastreamento da página inicial. O sistema mostra a situação atual da carga, atualizada ao longo de toda a rota, 24 horas por dia.</div>
              </details>
              <details className="faq-item">
                <summary>O que é o CT-e (Conhecimento de Transporte Eletrônico)? <span className="plus"><svg width="12" height="12"><use href="#i-plus"></use></svg></span></summary>
                <div className="faq-answer">É um documento de existência exclusivamente digital, emitido e armazenado eletronicamente, que documenta para fins fiscais a prestação de um serviço de transporte de cargas. É o número que você usa para consultar o andamento da sua entrega.</div>
              </details>
              <details className="faq-item">
                <summary>O que é cubagem e como ela é calculada? <span className="plus"><svg width="12" height="12"><use href="#i-plus"></use></svg></span></summary>
                <div className="faq-answer">Cubagem é a relação entre o peso e o volume de uma carga. Ela é calculada multiplicando altura × largura × profundidade × um fator de cubagem específico do modal de transporte, e é usada para definir o valor do frete quando o volume pesa mais que o peso real.</div>
              </details>
              <details className="faq-item">
                <summary>Quais documentos preciso apresentar para o envio de uma carga? <span className="plus"><svg width="12" height="12"><use href="#i-plus"></use></svg></span></summary>
                <div className="faq-answer">Nota fiscal da mercadoria e, quando aplicável, romaneio de carga. Nossa equipe comercial orienta sobre exigências específicas conforme o tipo de carga e o destino — fale conosco antes da coleta para confirmar tudo com antecedência.</div>
              </details>
            </div>
          </div>

          <div className="legal-section" style={{ marginBottom: 56 }}>
            <h2 style={{ fontSize: 20, marginBottom: 20 }}>Cotação e cargas</h2>
            <div className="faq-list">
              <details className="faq-item">
                <summary>Como solicito uma cotação de frete? <span className="plus"><svg width="12" height="12"><use href="#i-plus"></use></svg></span></summary>
                <div className="faq-answer">Preencha o formulário na página de <a href="/contato">Contato</a> com origem, destino e características da carga, ou ligue para 0800 000 0000. Nossa equipe comercial responde em até 24 horas.</div>
              </details>
              <details className="faq-item">
                <summary>Quais tipos de carga a Rotalog transporta? <span className="plus"><svg width="12" height="12"><use href="#i-plus"></use></svg></span></summary>
                <div className="faq-answer">Atuamos com distribuição urbana, armazenagem, transferência interestadual e operação de contêiner. Para restrições específicas de item, confirme com nossa equipe comercial antes do envio.</div>
              </details>
              <details className="faq-item">
                <summary>A Rotalog atende todo o Brasil? <span className="plus"><svg width="12" height="12"><use href="#i-plus"></use></svg></span></summary>
                <div className="faq-answer">Operamos a partir da matriz em Ponta Grossa/PR, com filiais em Guarulhos/SP e Camaçari/BA, cobrindo rotas nessas regiões. Consulte a página de <a href="/#unidades">Unidades</a> para mais detalhes.</div>
              </details>
            </div>
          </div>

          <div className="legal-section" style={{ marginBottom: 56 }}>
            <h2 style={{ fontSize: 20, marginBottom: 20 }}>Seminovos</h2>
            <div className="faq-list">
              <details className="faq-item">
                <summary>Como funciona a compra de um caminhão seminovo? <span className="plus"><svg width="12" height="12"><use href="#i-plus"></use></svg></span></summary>
                <div className="faq-answer">Todos os veículos anunciados em <a href="/seminovos">Seminovos</a> vêm da nossa própria frota, passam por inspeção técnica completa e têm documentação regularizada. Solicite informações pelo formulário da página para agendar uma visita.</div>
              </details>
              <details className="faq-item">
                <summary>Os veículos têm garantia? <span className="plus"><svg width="12" height="12"><use href="#i-plus"></use></svg></span></summary>
                <div className="faq-answer">Sim, oferecemos garantia estendida e histórico completo de manutenções para cada veículo vendido.</div>
              </details>
            </div>
          </div>

          <div className="legal-section">
            <h2 style={{ fontSize: 20, marginBottom: 20 }}>Carreira</h2>
            <div className="faq-list">
              <details className="faq-item">
                <summary>Como faço para trabalhar na Rotalog? <span className="plus"><svg width="12" height="12"><use href="#i-plus"></use></svg></span></summary>
                <div className="faq-answer">Acesse <a href="/trabalhe-conosco">Trabalhe Conosco</a> e envie seu currículo pelo formulário. Nosso processo seletivo tem 4 etapas e leva, em média, de 1 a 3 semanas do primeiro contato ao onboarding.</div>
              </details>
              <details className="faq-item">
                <summary>Quais vagas a Rotalog costuma abrir? <span className="plus"><svg width="12" height="12"><use href="#i-plus"></use></svg></span></summary>
                <div className="faq-answer">As oportunidades mais comuns são para Motorista Rodoviário, Operador Logístico e Atendimento ao Cliente. Cadastre seu currículo mesmo sem vaga aberta no momento.</div>
              </details>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
