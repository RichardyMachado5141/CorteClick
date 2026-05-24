# CorteClick

Plataforma de agendamento para barbearias — frontend interativo com Laravel Blade, Tailwind CSS e Vite.

## Visual

- Estilo clean inspirado em SaaS moderno (referência Bling)
- Paleta: branco, cinza claro, vermelho vinho (`#8b2942`), preto suave
- Microinterações, toasts, modais, loading states
- Dados simulados com `localStorage` para persistência entre páginas

## Executar

```bash
composer install
npm install
npm run dev
php artisan serve
```

Acesse: **http://127.0.0.1:8000**

## Rotas

| Rota | Página |
|------|--------|
| `/login` | Login |
| `/perfil` | Seleção de perfil |
| `/cliente/dashboard` | Dashboard cliente + agendamento |
| `/cliente/agendamentos` | Meus agendamentos |
| `/profissional/dashboard` | Dashboard profissional + agenda |
| `/profissional/servicos` | Meus serviços |
| `/admin/dashboard` | Dashboard admin |
| `/admin/usuarios` | Usuários |
| `/admin/agendamentos` | Agendamentos |

## Interações simuladas

- **Buscar horários** — gera grade dinâmica conforme serviço/profissional/data
- **Agendar** — confirmação em modal + toast + salva em localStorage
- **Cancelar agendamento** — atualiza status na lista
- **Filtros** — agendamentos (status), usuários (busca/perfil), admin agendamentos
- **Serviços** — adicionar, editar e excluir cards dinamicamente
- **Toggle usuário** — ativar/desativar conta

## Estrutura JS

```
resources/js/
  core/ui.js          # Toast, Modal, Loading
  core/store.js       # Estado + localStorage
  pages/              # Lógica por página
  app.js              # Bootstrap
```


