# Checklist Fotográfico de Frota

Sistema completo (não é protótipo): o motorista tira fotos de cada item do
checklist (pode tirar mais de uma por item) e segue viagem — a análise por
IA roda **depois, em segundo plano**, num worker agendado pra rodar sozinho
a cada minuto. O gestor tem um painel com login, lista de checklists,
itens críticos em aberto, histórico por placa e alerta por e-mail
automático quando algo dá crítico.

## 1. Ligar os serviços do Laragon

Abra o Laragon e clique em **Start All** (ou clique com o botão direito em
MySQL e Apache e dê Start em cada um). Isso precisa estar rodando sempre
que for usar o sistema.

## 2. Criar o banco de dados

Com o MySQL rodando:

```bash
"C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe" --default-character-set=utf8mb4 -u root < "C:\Users\ramon\OneDrive\Documentos\GitHub\transdell\sistema\database\schema.sql"
```

Isso cria o banco `sistema_do_paulo`, as tabelas, os 6 itens de checklist
padrão e um usuário gestor inicial:

- **E-mail:** admin@frota.local
- **Senha:** mudar123 — **troque assim que entrar** (menu "Meu perfil").

## 3. A IA que analisa as fotos

O sistema tenta os provedores na ordem definida em `VISION_PROVIDER` no
`.env` (ex: `gemini,openrouter`) — se um falhar, tenta o próximo antes de
desistir. Provedores suportados: `gemini`, `openrouter`, `groq`, `anthropic`.
Cada um tem sua própria chave (`GEMINI_API_KEY`, `OPENROUTER_API_KEY`,
etc) no mesmo arquivo `.env` — já preenchidas nessa reinstalação.

## 4. Acessar o sistema

Com o Laragon rodando, o Apache já serve a pasta automaticamente em:

- **Motorista:** http://sistema-do-paulo.test/
- **Painel do gestor:** http://sistema-do-paulo.test/painel/login.php

Se o domínio `.test` não abrir, use `http://localhost/sistema-do-paulo/`.

Pra acessar do celular na mesma rede Wi-Fi, precisa reconfigurar o alias do
IP local no vhost do Apache (isso não sobreviveu à reinstalação — avise
quando for testar do celular de novo que eu reconfiguro).

## 5. O worker de análise (a parte que roda sozinha)

As fotos **não são analisadas na hora** — o motorista só sobe a foto
(instantâneo) e segue o checklist. Quem analisa é o script
`worker/processar_fotos.php`, chamado por uma **Tarefa Agendada do
Windows** (`ChecklistFrotaWorker`).

**Importante:** essa tarefa está **desativada** por padrão após a
reinstalação (foi pausada a pedido antes do Laragon ter sido apagado sem
querer). Avise quando quiser reativá-la — sem ela, as fotos ficam
paradas em "aguardando análise automática" pra sempre, e vão precisar ser
processadas rodando o worker manualmente ou reativando a tarefa.

Cada foto tenta até 5 vezes (a cada execução do worker) se a IA falhar. Se
esgotar as tentativas, a foto fica marcada **"precisa de revisão manual"**
e aparece no painel do gestor com 3 botões (OK/Atenção/Crítico) pra
classificar na mão.

## 6. Especialização de itens (ex: pneu)

Em **Itens do checklist**, cada item tem um "tipo de análise". O item
"Pneus" já vem marcado como **Pneu**, o que faz a IA avaliar
profundidade de sulco, desgaste irregular, TWI, cortes/bolhas e devolver
uma classificação (Excelente/Bom/Regular/Careca) + estimativa de % de
vida útil, em vez do OK/Atenção/Crítico genérico. Dá pra especializar
outros itens do mesmo jeito no futuro (basta eu adicionar o tipo em
`includes/VisionPrompt.php`).

## 7. Uso no dia a dia

1. No painel, cadastre os motoristas (**Motoristas**) — cada um recebe um
   PIN de 4 dígitos que ele usa pra entrar no checklist pelo celular.
2. Ajuste os itens do checklist em **Itens do checklist** se quiser.
3. O motorista abre o link no celular, digita PIN + placa, tira as fotos
   (pode tirar mais de uma por item) e clica em "Enviar checklist".
4. Assim que a tarefa agendada estiver ativa, em até 1 minuto o worker
   analisa tudo. O painel mostra "Analisando..." enquanto isso.
5. Se der crítico, o e-mail de alerta sai sozinho.

## 8. E-mail de alerta

Por padrão o `.env` aponta pro **Mailpit** (já vem com o Laragon) — captura
os e-mails localmente, sem precisar de senha de verdade. Veja os e-mails em
http://localhost:8025.

Pra enviar de verdade (ex: Gmail), troque no `.env`:

```
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USER=seuemail@gmail.com
MAIL_PASS=sua-senha-de-app-do-gmail
MAIL_TO=email-do-gestor@exemplo.com
```

## 9. Quando quiser colocar online (fora da sua máquina)

Hoje o sistema só funciona na rede onde o Laragon estiver rodando. Pra
funcionar de qualquer lugar, o caminho mais simples é subir a pasta pra
uma hospedagem PHP+MySQL, importar o `schema.sql` lá, apontar o `.env` pro
banco novo, e trocar a Tarefa Agendada do Windows por um cron job. Me
chame quando for fazer isso.

## Estrutura do projeto

```
config/         conexão com banco + variáveis de ambiente
includes/       autenticação, upload de foto, provedores de IA, e-mail
api/            endpoints chamados pelo checklist do motorista (JSON)
worker/         processar_fotos.php — roda sozinho via Tarefa Agendada
painel/         área do gestor (login obrigatório)
assets/         CSS e JS
database/       schema.sql
uploads/fotos/  fotos enviadas pelos motoristas (não vai pro git)
```
