import "./globals.css";
import IconSprite from "../components/IconSprite";
import Header from "../components/Header";
import Footer from "../components/Footer";

export const metadata = {
  title: {
    default: "Rotalog — Transporte Rodoviário de Cargas",
    template: "%s — Rotalog",
  },
  description:
    "Rotalog Transportes: distribuição urbana, armazenagem, transferência interestadual e operação de contêiner, da matriz em Ponta Grossa/PR para todo o Brasil.",
};

export default function RootLayout({ children }) {
  return (
    <html lang="pt-BR">
      <head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link
          href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap"
          rel="stylesheet"
        />
      </head>
      <body>
        <IconSprite />
        <Header />
        {children}
        <Footer />
      </body>
    </html>
  );
}
