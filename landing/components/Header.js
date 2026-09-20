import Link from "next/link";

export default function Header() {
  return (
    <header className="site-header">
      <div className="utility-bar">
        <div className="left">
          <a href="tel:08000000000"><svg className="icon" width="13" height="13"><use href="#i-phone"></use></svg>0800 000 0000</a>
          <a href="mailto:comercial@rotalog.com.br"><svg className="icon" width="13" height="13"><use href="#i-mail"></use></svg>comercial@rotalog.com.br</a>
        </div>
        <div className="right">
          <Link href="/#rastreio"><svg className="icon" width="13" height="13"><use href="#i-search"></use></svg>Rastrear Carga</Link>
          <a href="#" className="client-link">Área do Cliente</a>
          <div className="social">
            <a href="#" aria-label="Instagram"><span className="insta-icon" style={{ width: 15, height: 15 }}><svg width="15" height="15"><use href="#i-insta"></use></svg><svg width="15" height="15" className="insta-brand"><use href="#i-insta-brand"></use></svg></span></a>
            <a href="#" aria-label="LinkedIn" className="hover-linkedin"><svg width="15" height="15"><use href="#i-linkedin"></use></svg></a>
          </div>
        </div>
      </div>
      <nav className="main-nav">
        <Link href="/" className="logo">ROTA<span>LOG</span></Link>
        <ul className="nav-links">
          <li><Link href="/#servicos">Serviços</Link></li>
          <li><Link href="/sobre">Sobre</Link></li>
          <li><Link href="/seminovos">Seminovos</Link></li>
          <li><Link href="/#unidades">Unidades</Link></li>
          <li><Link href="/contato">Contato</Link></li>
        </ul>
        <Link href="/#cotacao" className="btn btn-primary">Solicitar Cotação</Link>
      </nav>
    </header>
  );
}
