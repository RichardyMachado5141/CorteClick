````md
# CorteClick

Sistema web de agendamento para barbearias desenvolvido com Laravel, Vite, JavaScript e SQLite.

---

# Sobre o Projeto

O CorteClick é uma plataforma de gerenciamento e agendamento para barbearias, permitindo que clientes realizem agendamentos online, profissionais acompanhem suas agendas e administradores gerenciem usuários e horários.

O projeto foi desenvolvido com foco em organização visual, experiência do usuário e integração entre front-end, back-end e banco de dados.

---

# Funcionalidades

## Cliente
- Login
- Dashboard do cliente
- Busca de horários disponíveis
- Agendamento de serviços
- Visualização de agendamentos
- Cancelamento de agendamentos

---

## Profissional
- Dashboard profissional
- Agenda do dia
- Gerenciamento de serviços
- Visualização de horários pendentes e confirmados

---

## Administrador
- Dashboard administrativo
- Gerenciamento de usuários
- Visualização de agendamentos
- Controle geral da plataforma

---

# Tecnologias Utilizadas

- Laravel
- JavaScript
- Vite
- Tailwind CSS
- SQLite
- HTML5
- CSS3

---

# Interface

O sistema utiliza um visual moderno inspirado em plataformas SaaS, com:
- Interface clean
- Layout responsivo
- Microinterações
- Modais
- Toasts
- Feedback visual dinâmico

Paleta principal:
- Branco
- Cinza claro
- Preto suave
- Vermelho vinho (#8b2942)

---

# Banco de Dados

O projeto utiliza SQLite integrado ao Laravel.

As informações de agendamento são armazenadas no banco através da integração entre front-end e back-end utilizando requisições HTTP.

---

# Como Executar o Projeto

## Instalar dependências

```bash
composer install
npm install
````

---

## Executar o Vite

```bash
npm run dev
```

---

## Executar o Laravel

```bash
php artisan serve
```

---

## Rodar as migrations

```bash
php artisan migrate
```

---

# Acesso ao Sistema

Após iniciar o projeto:

```txt
http://127.0.0.1:8000
```

---

# Tipos de Login

## Cliente

Utilize qualquer e-mail comum:

```txt
cliente@gmail.com
```

---

## Profissional

```txt
carlos@prof.com
```

---

## Administrador

```txt
richardy@adm.com
```

---

# Rotas do Sistema

| Rota                      | Página                        |
| ------------------------- | ----------------------------- |
| `/login`                  | Login                         |
| `/perfil`                 | Seleção de perfil             |
| `/cliente/dashboard`      | Dashboard cliente             |
| `/cliente/agendamentos`   | Agendamentos do cliente       |
| `/profissional/dashboard` | Dashboard profissional        |
| `/profissional/servicos`  | Serviços do profissional      |
| `/admin/dashboard`        | Dashboard administrativo      |
| `/admin/usuarios`         | Gerenciamento de usuários     |
| `/admin/agendamentos`     | Gerenciamento de agendamentos |

---

# Estrutura do Projeto

```txt
resources/js/
  core/
    ui.js
    store.js

  pages/
    cliente-dashboard.js
    profissional-dashboard.js
    admin-dashboard.js

  app.js
```

---

# Integração Front-end e Back-end

O sistema possui integração entre:

* Front-end
* Back-end Laravel
* Banco de dados SQLite

Os agendamentos realizados pelo cliente são enviados ao backend e armazenados no banco de dados.

---

# Organização do Repositório

O repositório contém:

* Sistema integrado final
* Backend original desenvolvido separadamente pela equipe
* Estrutura MVC organizada

---

# Integrantes

* Richardy Machado Paulino da Silva
* Yasmin Santos Lopes
* Mário Luiz Alves Coutinho
* Bruno Mateus

```
```
