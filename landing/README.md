# Rotalog Transportes — site institucional

Site institucional construído em [Next.js](https://nextjs.org) (App Router), pronto para deploy na [Vercel](https://vercel.com).

> **Nota:** este projeto é um template. Nome da empresa, CNPJ, telefones, e-mails, endereço e links de redes sociais são **dados fictícios de exemplo** — substitua tudo em `components/Header.js`, `components/Footer.js` e nas páginas dentro de `app/` pelos dados reais antes de publicar em produção.

## Rodando localmente

```bash
npm install
npm run dev
```

Abra [http://localhost:3000](http://localhost:3000).

## Build de produção

```bash
npm run build
npm run start
```

## Estrutura

- `app/` — rotas (App Router), uma pasta por página (`/sobre`, `/contato`, `/seminovos`, `/trabalhe-conosco`, `/perguntas-frequentes`, `/politica-privacidade`, `/codigo-etica`) mais `app/page.js` para a home.
- `components/` — `Header`, `Footer`, `IconSprite` (sprite de ícones SVG compartilhado) e os formulários (`ContactForm`, `VehicleInquiryForm`, `ApplicationForm`), que são Client Components por terem interatividade.
- `app/globals.css` — estilos globais (tokens de design, tipografia e componentes visuais usados em todas as páginas).

Os formulários do site não têm backend: eles apenas previnem o reload da página no submit (`onSubmit` com `preventDefault`). Para receber os envios de verdade, plugue um endpoint (API route do Next.js, serviço de formulário como Formspree, etc.) em cada componente de formulário.

## Deploy no GitHub + Vercel

1. Crie um repositório novo no GitHub e suba esta pasta (`landing/`) como raiz do repositório, ou aponte o "Root Directory" do projeto na Vercel para `landing/` se mantiver este repositório monorepo.
2. Em [vercel.com/new](https://vercel.com/new), importe o repositório — a Vercel detecta o Next.js automaticamente e não exige configuração adicional.
3. Cada push na branch principal gera um novo deploy de produção.
