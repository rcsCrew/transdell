import Link from "next/link";

export default function Footer() {
  return (
    <footer className="site-footer">
      <div className="footer-grid">
        <div className="footer-brand">
          <Link href="/" className="logo">ROTA<span>LOG</span></Link>
          <p>Excelência em transporte de cargas — da matriz em Ponta Grossa para todo o Brasil.</p>
          <div className="footer-social">
            <a href="#" aria-label="Instagram"><span className="insta-icon" style={{ width: 16, height: 16 }}><svg width="16" height="16"><use href="#i-insta"></use></svg><svg width="16" height="16" className="insta-brand"><use href="#i-insta-brand"></use></svg></span></a>
            <a href="#" aria-label="LinkedIn" className="hover-linkedin"><svg width="16" height="16"><use href="#i-linkedin"></use></svg></a>
            <a href="#" aria-label="WhatsApp" className="hover-whatsapp"><svg width="16" height="16"><use href="#i-chat"></use></svg></a>
          </div>
        </div>
        <div className="footer-col">
          <h4>Institucional</h4>
          <Link href="/sobre">Sobre</Link>
          <Link href="/trabalhe-conosco">Trabalhe Conosco</Link>
          <Link href="/seminovos">Seminovos</Link>
          <Link href="/perguntas-frequentes">Perguntas Frequentes</Link>
        </div>
        <div className="footer-col">
          <h4>Serviços</h4>
          <Link href="/#servicos">Distribuição Urbana</Link>
          <Link href="/#servicos">Armazenagem</Link>
          <Link href="/#servicos">Transferência Brasil</Link>
          <Link href="/#servicos">Operação Container</Link>
        </div>
        <div className="footer-col">
          <h4>Atendimento</h4>
          <div className="footer-contact">
            <span className="mono">0800 000 0000</span>
            <span>adm@rotalog.com.br</span>
            <span>comercial@rotalog.com.br</span>
            <span>Seg–Sex 08h–18h · Sáb 08h–12h</span>
          </div>
        </div>
      </div>
      <div className="footer-bottom">
        <span>© 2026 Rotalog Transporte de Cargas LTDA · CNPJ 00.000.000/0001-00</span>
        <div className="legal">
          <Link href="/politica-privacidade">Política de Privacidade</Link>
          <Link href="/codigo-etica">Código de Ética</Link>
        </div>
      </div>
    </footer>
  );
}
