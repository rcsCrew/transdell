export const metadata = {
  title: "Política de Privacidade",
  description:
    "Como a Rotalog coleta, usa e protege dados pessoais, em conformidade com a Lei Geral de Proteção de Dados (LGPD).",
};

export default function PoliticaPrivacidadePage() {
  return (
    <>
      <section className="page-hero" style={{ paddingBottom: 56 }}>
        <div className="page-hero-inner">
          <div className="eyebrow">Privacidade</div>
          <h1 style={{ fontSize: "clamp(28px,4vw,40px)" }}>Política de Privacidade</h1>
          <p>Como coletamos, usamos e protegemos os seus dados pessoais.</p>
        </div>
      </section>

      <section className="section">
        <div className="container">
          <div className="legal-updated">Última atualização: [data a definir]</div>
          <div className="callout-note">
            Este documento organiza os tópicos exigidos pela LGPD (Lei nº 13.709/2018) para uma política de privacidade. Os trechos entre colchetes precisam ser preenchidos e validados pelo time jurídico da Rotalog antes da publicação oficial — o conteúdo abaixo não deve ser tratado como parecer legal.
          </div>

          <div className="legal-layout">
            <aside className="legal-toc">
              <h3>Nesta página</h3>
              <ol>
                <li><a href="#introducao">Introdução</a></li>
                <li><a href="#dados-coletados">Dados que coletamos</a></li>
                <li><a href="#uso-dados">Como usamos seus dados</a></li>
                <li><a href="#compartilhamento">Compartilhamento</a></li>
                <li><a href="#cookies">Cookies</a></li>
                <li><a href="#direitos">Seus direitos</a></li>
                <li><a href="#seguranca">Segurança</a></li>
                <li><a href="#retencao">Retenção de dados</a></li>
                <li><a href="#alteracoes">Alterações</a></li>
                <li><a href="#contato-dpo">Fale com o encarregado</a></li>
              </ol>
            </aside>

            <div>
              <div className="legal-section" id="introducao">
                <h2>1. Introdução</h2>
                <p>A Rotalog Transporte de Cargas LTDA (&quot;Rotalog&quot;, &quot;nós&quot;) respeita a privacidade de clientes, parceiros, candidatos e visitantes do site. Esta política explica quais dados pessoais coletamos, para que finalidades e quais direitos você tem sobre eles, em conformidade com a Lei Geral de Proteção de Dados (Lei nº 13.709/2018).</p>
              </div>

              <div className="legal-section" id="dados-coletados">
                <h2>2. Dados que coletamos</h2>
                <p>Coletamos dados fornecidos diretamente por você em nossos formulários e canais de atendimento, entre eles:</p>
                <ul>
                  <li>Dados de identificação: nome, e-mail, telefone e empresa (formulário de contato e cotação)</li>
                  <li>Dados de candidatura: nome, cidade, telefone, e-mail, currículo e experiência profissional (formulário de trabalhe conosco)</li>
                  <li>Dados de interesse comercial: veículo de interesse, mensagens enviadas (formulário de seminovos)</li>
                  <li>Dados de navegação: [detalhar cookies e ferramentas de analytics usadas, se houver]</li>
                </ul>
              </div>

              <div className="legal-section" id="uso-dados">
                <h2>3. Como usamos seus dados</h2>
                <p>Usamos os dados coletados para:</p>
                <ul>
                  <li>Responder solicitações de cotação, contato e informações sobre veículos seminovos</li>
                  <li>Processar candidaturas de emprego</li>
                  <li>Cumprir obrigações legais e fiscais relacionadas ao transporte de cargas</li>
                  <li>Melhorar nossos serviços e a experiência no site</li>
                </ul>
              </div>

              <div className="legal-section" id="compartilhamento">
                <h2>4. Compartilhamento de dados</h2>
                <p>[Listar aqui os terceiros com quem dados são efetivamente compartilhados — por exemplo, prestadores de serviço de e-mail, ferramentas de CRM ou parceiros logísticos — e a base legal para cada compartilhamento.] A Rotalog não vende dados pessoais a terceiros.</p>
              </div>

              <div className="legal-section" id="cookies">
                <h2>5. Cookies e tecnologias de rastreamento</h2>
                <p>[Descrever quais cookies o site efetivamente utiliza — por exemplo, cookies de analytics ou de preferências — e oferecer opção de gerenciamento quando aplicável.]</p>
              </div>

              <div className="legal-section" id="direitos">
                <h2>6. Seus direitos, conforme a LGPD</h2>
                <p>Você tem direito a:</p>
                <ul>
                  <li>Confirmar a existência de tratamento dos seus dados</li>
                  <li>Acessar, corrigir ou atualizar seus dados</li>
                  <li>Solicitar a anonimização, bloqueio ou eliminação de dados desnecessários</li>
                  <li>Solicitar a portabilidade dos dados a outro fornecedor</li>
                  <li>Revogar o consentimento e solicitar a eliminação dos dados tratados com base nele</li>
                </ul>
                <p>Para exercer esses direitos, entre em contato pelos canais listados na seção 10.</p>
              </div>

              <div className="legal-section" id="seguranca">
                <h2>7. Segurança da informação</h2>
                <p>Adotamos medidas técnicas e administrativas razoáveis para proteger os dados pessoais contra acessos não autorizados e situações de destruição, perda, alteração ou vazamento. [Detalhar medidas específicas adotadas, se a empresa quiser divulgá-las.]</p>
              </div>

              <div className="legal-section" id="retencao">
                <h2>8. Retenção de dados</h2>
                <p>Mantemos os dados pessoais pelo tempo necessário para cumprir as finalidades descritas nesta política, ou conforme exigido por lei. [Definir prazos específicos de retenção por tipo de dado.]</p>
              </div>

              <div className="legal-section" id="alteracoes">
                <h2>9. Alterações nesta política</h2>
                <p>Esta política pode ser atualizada periodicamente. A data da última atualização é sempre indicada no topo desta página.</p>
              </div>

              <div className="legal-section" id="contato-dpo">
                <h2>10. Fale com o encarregado de dados</h2>
                <p>Dúvidas sobre esta política ou sobre o tratamento dos seus dados pessoais podem ser enviadas para <a href="mailto:adm@rotalog.com.br">adm@rotalog.com.br</a> [ou o e-mail específico do Encarregado de Dados / DPO, quando definido].</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
