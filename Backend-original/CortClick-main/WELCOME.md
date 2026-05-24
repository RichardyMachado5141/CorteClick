# ✨ Bem-vindo ao CorteClick!

## 🎯 O que você encontra aqui

Este é o projeto **CorteClick** - um sistema completo de agendamento para barbearias e salões de beleza, desenvolvido durante a disciplina de Engenharia de Requisitos.

## 📂 Estrutura do Projeto

```
cortclick/
│
├── 📱 app/
│   ├── Http/Controllers/Api/          # Controllers da API REST
│   │   ├── AuthController.php          # Autenticação (login/registro)
│   │   ├── UserController.php          # Gerenciamento de usuários
│   │   ├── ProfessionalController.php  # Gerenciamento de profissionais
│   │   ├── ServiceController.php       # Gerenciamento de serviços
│   │   ├── AppointmentController.php   # Gerenciamento de agendamentos
│   │   └── AvailabilityController.php  # Consulta de disponibilidade
│   │
│   └── Models/                         # Modelos Eloquent (Banco de dados)
│       ├── User.php                    # Usuário
│       ├── Professional.php            # Profissional
│       ├── Service.php                 # Serviço
│       ├── Appointment.php             # Agendamento
│       └── UserLog.php                 # Log de ações
│
├── 🗄️ database/
│   ├── migrations/                     # Criação das tabelas
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2026_01_01_000001_create_professionals_table.php
│   │   ├── 2026_01_01_000002_create_services_table.php
│   │   ├── 2026_01_01_000003_create_appointments_table.php
│   │   ├── 2026_01_01_000004_create_user_logs_table.php
│   │   └── 2026_01_01_000005_alter_users_table.php
│   │
│   ├── factories/                      # Dados aleatórios para testes
│   │   ├── UserFactory.php
│   │   ├── ProfessionalFactory.php
│   │   ├── ServiceFactory.php
│   │   └── AppointmentFactory.php
│   │
│   ├── seeders/                        # Popula o banco com dados
│   │   └── DatabaseSeeder.php
│   │
│   └── database.sql                    # SQL completo do banco
│
├── 🛣️ routes/
│   ├── api.php                         # Rotas da API REST
│   └── web.php
│
├── 📚 Documentação
│   ├── README.md                       # Documentação completa
│   ├── SETUP.md                        # Guia de instalação rápida
│   ├── API_EXAMPLES.md                 # Exemplos de requisições
│   └── config/                         # Configuração da aplicação
│
└── 📦 Arquivos de Configuração
    ├── composer.json                   # Dependências PHP
    ├── .env.example                    # Variáveis de ambiente
    ├── artisan                         # CLI do Laravel
    └── package.json                    # Dependências Node.js
```

## 🚀 Quick Start (30 segundos)

```bash
# 1. Instale as dependências
composer install
npm install

# 2. Configure o ambiente
cp .env.example .env
php artisan key:generate

# 3. Crie o banco de dados (MySQL)
mysql -u root -p -e "CREATE DATABASE corteClick;"

# 4. Execute as migrações
php artisan migrate --seed

# 5. Inicie o servidor
php artisan serve
```

**Acesse:** `http://localhost:8000`

## 📡 API Endpoints Principais

### Autenticação
- `POST /api/register` - Registrar novo usuário
- `POST /api/login` - Fazer login
- `POST /api/logout` - Fazer logout
- `GET /api/profile` - Obter perfil do usuário

### Profissionais
- `GET /api/professionals` - Listar profissionais
- `POST /api/professionals` - Criar profissional
- `PUT /api/professionals/{id}` - Atualizar profissional
- `DELETE /api/professionals/{id}` - Deletar profissional

### Serviços
- `GET /api/services` - Listar serviços
- `POST /api/services` - Criar serviço
- `PUT /api/services/{id}` - Atualizar serviço
- `DELETE /api/services/{id}` - Deletar serviço

### Agendamentos
- `GET /api/appointments` - Listar agendamentos
- `POST /api/appointments` - Criar agendamento
- `PUT /api/appointments/{id}/status` - Atualizar status
- `PUT /api/appointments/{id}/cancel` - Cancelar agendamento

### Disponibilidade
- `GET /api/availability/slots/{professionalId}` - Horários de um dia
- `GET /api/availability/range/{professionalId}` - Horários de um período

## 🔐 Autenticação

A API usa **Laravel Sanctum** para autenticação via tokens. Todos os endpoints autenticados requerem:

```
Authorization: Bearer {seu_token_aqui}
```

## 📊 Modelos de Dados

### User (Usuário)
- `id`, `name`, `email`, `phone`, `role` (client/professional/admin)
- `password` (hash), `email_verified_at`, timestamps

### Professional (Profissional)
- `id`, `user_id`, `specialty`, `description`
- `phone`, `start_time`, `end_time`
- `available_days` (JSON), timestamps

### Service (Serviço)
- `id`, `professional_id`, `name`, `price`, `duration`
- `description`, `is_active`, timestamps

### Appointment (Agendamento)
- `id`, `client_id`, `professional_id`, `service_id`
- `appointment_date`, `status` (pending/confirmed/completed/cancelled)
- `notes`, timestamps

### UserLog (Log de Ações)
- `id`, `user_id`, `action`, `model`, `model_id`
- `data` (JSON), `ip_address`, `user_agent`, timestamps

## 👨‍💼 Papéis de Usuário

### Client (Cliente)
- Registrar conta
- Visualizar profissionais e serviços
- Criar e cancelar agendamentos
- Visualizar seus agendamentos

### Professional (Profissional)
- Registrar perfil profissional
- Cadastrar serviços
- Visualizar sua agenda
- Confirmar/completar agendamentos
- Consultar horários livres

### Admin (Administrador)
- Acesso total ao sistema
- Gerenciar usuários
- Gerenciar profissionais
- Visualizar logs e auditoria

## 🧪 Dados de Teste

Após executar `php artisan migrate --seed`:

```
Admin:
  Email: admin@corteclick.com
  
5 Clientes com emails aleatórios
3 Profissionais com emails aleatórios
10 Agendamentos de teste
```

## 📝 Requisitos Implementados

✅ RF01 - Cadastro de usuários
✅ RF02 - Autenticação/login
✅ RF03 - Agendamentos por clientes
✅ RF04 - Cancelamento de agendamentos
✅ RF05 - Visualização de agenda (profissional)
✅ RF06 - Cadastro de serviços
✅ RF07 - Busca de horários disponíveis
✅ RF08 - Gerenciamento de usuários (admin)
✅ RF09 - Registro de agendamentos
✅ RF10 - Confirmação de agendamento

## 🔗 Links Importantes

- **Laravel Docs**: https://laravel.com/docs
- **Sanctum**: https://laravel.com/docs/sanctum
- **Eloquent ORM**: https://laravel.com/docs/eloquent
- **RESTful API**: https://restfulapi.net

## 📚 Documentação Adicional

- [README.md](README.md) - Documentação completa do projeto
- [SETUP.md](SETUP.md) - Guia de instalação passo a passo
- [API_EXAMPLES.md](API_EXAMPLES.md) - Exemplos de requisições com cURL e Postman

## 👥 Integrantes do Grupo

- Bruno Mateus
- Richardy Machado Paulino da Silva
- Mario Luiz Alves Coutinho
- Yasmim Santos Lopes
- Thiago Barros

**UNITINS** | TADS | 3º Período | 2026

## ❓ Dúvidas?

1. Verifique a documentação em `README.md` e `SETUP.md`
2. Veja exemplos de API em `API_EXAMPLES.md`
3. Consulte o código dos controllers em `app/Http/Controllers/Api/`
4. Revise os modelos em `app/Models/`

## 🎓 Aprendizados

Este projeto demonstra:

- ✅ Arquitetura REST com Laravel
- ✅ Autenticação com tokens (Sanctum)
- ✅ Modelagem de dados (Eloquent)
- ✅ Migrações de banco de dados
- ✅ Validação e tratamento de erros
- ✅ Logs e auditoria
- ✅ Soft deletes
- ✅ Relacionamentos entre modelos
- ✅ Escopos e queries otimizadas
- ✅ Boas práticas de código PHP/Laravel

---

**Desenvolvido com ❤️ pela turma de TADS - UNITINS**
