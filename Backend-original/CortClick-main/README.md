# 🎯 CorteClick - Sistema de Agendamento para Barbearias e Salões

[![Laravel](https://img.shields.io/badge/Laravel-13.8-red?style=flat-square)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-blue?style=flat-square)](https://www.php.net)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

Sistema web completo para gerenciamento de agendamentos em barbearias e salões de beleza, desenvolvido com Laravel 13 e MySQL.

## 📋 Descrição do Projeto

O **CorteClick** é uma aplicação moderna que resolve os principais problemas enfrentados por barbearias e salões:

- ✅ **Agendamentos Online**: Clientes podem agendar serviços online
- ✅ **Gerenciamento de Horários**: Profissionais organizam sua agenda facilmente
- ✅ **Cadastro de Serviços**: Sistema de serviços com preços e duração
- ✅ **Consulta de Disponibilidade**: Visualização automática de horários livres
- ✅ **Histórico de Agendamentos**: Registro completo de todas as interações
- ✅ **Autenticação Segura**: Sistema de autenticação com JWT tokens
- ✅ **Gestão de Usuários**: Administração de clientes, profissionais e admin

## 🛠️ Tecnologias Utilizadas

### Backend
- **Laravel 13.8**: Framework PHP moderno
- **MySQL/MariaDB**: Banco de dados relacional
- **Laravel Sanctum**: Autenticação via API tokens
- **Eloquent ORM**: Mapeamento objeto-relacional

### Ferramentas
- **Composer**: Gerenciador de dependências PHP
- **Artisan**: CLI do Laravel
- **PHPUnit**: Framework de testes

## 📦 Requisitos do Sistema

- PHP 8.3+
- MySQL 5.7+ ou MariaDB 10.3+
- Composer 2.x
- Node.js 18+ (para ferramentas de build)
- Git

## 🚀 Como Executar o Sistema Localmente

### 1. Clone o repositório

```bash
git clone https://github.com/seu-usuario/cortclick.git
cd cortclick
```

### 2. Instale as dependências

```bash
composer install
npm install
```

### 3. Configure o arquivo .env

```bash
cp .env.example .env
php artisan key:generate
```

Configure as variáveis de banco de dados no `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=corteClick
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Execute as migrações

```bash
php artisan migrate
php artisan db:seed
```

### 5. Inicie o servidor

```bash
php artisan serve
```

O sistema estará disponível em `http://localhost:8000`

### 6. Teste a API

Você pode usar o Postman ou curl para testar os endpoints. Veja a documentação da API abaixo.

## 📚 Documentação da API

### Autenticação

#### Registro de Usuário
```http
POST /api/register
Content-Type: application/json

{
  "name": "João Silva",
  "email": "joao@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "11999999999",
  "role": "client"
}
```

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "joao@example.com",
  "password": "password123"
}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

#### Obter Perfil
```http
GET /api/profile
Authorization: Bearer {token}
```

### Profissionais

#### Listar Profissionais
```http
GET /api/professionals?page=1&specialty=Corte
```

#### Obter Detalhes do Profissional
```http
GET /api/professionals/{id}
```

#### Criar Profissional
```http
POST /api/professionals
Authorization: Bearer {token}
Content-Type: application/json

{
  "specialty": "Corte de Cabelo",
  "description": "Profissional com 10 anos de experiência",
  "phone": "11999999999",
  "start_time": "09:00",
  "end_time": "18:00",
  "available_days": ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday"]
}
```

#### Atualizar Profissional
```http
PUT /api/professionals/{id}
Authorization: Bearer {token}
```

#### Deletar Profissional
```http
DELETE /api/professionals/{id}
Authorization: Bearer {token}
```

### Serviços

#### Listar Serviços
```http
GET /api/services?page=1&active=true
```

#### Obter Detalhes do Serviço
```http
GET /api/services/{id}
```

#### Criar Serviço
```http
POST /api/services
Authorization: Bearer {token}
Content-Type: application/json

{
  "professional_id": 1,
  "name": "Corte de Cabelo",
  "price": 50.00,
  "duration": 30,
  "description": "Corte tradicional",
  "is_active": true
}
```

#### Atualizar Serviço
```http
PUT /api/services/{id}
Authorization: Bearer {token}
```

#### Deletar Serviço
```http
DELETE /api/services/{id}
Authorization: Bearer {token}
```

### Agendamentos

#### Listar Agendamentos
```http
GET /api/appointments?page=1&status=pending
Authorization: Bearer {token}
```

#### Criar Agendamento
```http
POST /api/appointments
Authorization: Bearer {token}
Content-Type: application/json

{
  "professional_id": 1,
  "service_id": 1,
  "appointment_date": "2026-01-20 14:30",
  "notes": "Cortar cabelo curto"
}
```

#### Atualizar Status do Agendamento
```http
PUT /api/appointments/{id}/status
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "confirmed"
}
```

#### Cancelar Agendamento
```http
PUT /api/appointments/{id}/cancel
Authorization: Bearer {token}
```

### Disponibilidade

#### Obter Horários Disponíveis (Um dia)
```http
GET /api/availability/slots/{professionalId}?date=2026-01-20&service_id=1&duration=30
```

#### Obter Horários Disponíveis (Intervalo de dias)
```http
GET /api/availability/range/{professionalId}?from_date=2026-01-20&to_date=2026-01-25&service_id=1
```

## 📁 Estrutura do Projeto

```
cortclick/
├── app/
│   ├── Http/Controllers/Api/
│   │   ├── AppointmentController.php
│   │   ├── AuthController.php
│   │   ├── AvailabilityController.php
│   │   ├── ProfessionalController.php
│   │   ├── ServiceController.php
│   │   └── UserController.php
│   └── Models/
│       ├── Appointment.php
│       ├── Professional.php
│       ├── Service.php
│       ├── User.php
│       └── UserLog.php
├── database/
│   ├── factories/
│   ├── migrations/
│   ├── seeders/
│   └── database.sql
├── routes/
│   ├── api.php
│   └── web.php
└── README.md
```

## 🔧 Modelos de Dados

### User
- ID, Nome, Email, Telefone
- Role (cliente, profissional, admin)
- Senha (hash), timestamps

### Professional
- ID, User ID, Especialidade
- Descrição, Telefone
- Horários (início/fim)
- Dias disponíveis (JSON)

### Service
- ID, Professional ID, Nome
- Preço, Duração (minutos)
- Descrição, Status ativo

### Appointment
- ID, Client ID, Professional ID, Service ID
- Data/Hora, Status
- Notas, Timestamps

### UserLog
- ID, User ID, Ação
- Modelo, Model ID, Dados
- IP Address, User Agent

## 🎯 Funcionalidades Implementadas

✅ Registro e autenticação de usuários
✅ CRUD completo de usuários
✅ CRUD de profissionais
✅ CRUD de serviços
✅ CRUD de agendamentos
✅ Busca de horários disponíveis
✅ Atualização de status de agendamentos
✅ Cancelamento de agendamentos
✅ Busca e filtros
✅ Registro de logs de usuário
✅ Proteção de rotas com autenticação
✅ Validação de dados de entrada
✅ Tratamento de erros robusto

## 📄 Integrantes do Grupo

- Bruno Mateus
- Richardy Machado Paulino da Silva
- Mario Luiz Alves Coutinho
- Yasmim Santos Lopes
- Thiago Barros

**UNITINS** - Universidade Estadual do Tocantins
**Curso**: TADS - Tecnologia da Análise e Desenvolvimento de Sistemas
**Disciplina**: Engenharia de Requisitos
**Período**: 3º Período | **Ano**: 2026 | **Local**: Xambióa - TO

## 📄 Licença

MIT License - veja LICENSE para detalhes

## 📞 Suporte

Para dúvidas, abra uma issue no repositório.

---

**Desenvolvido com ❤️ pela turma de TADS - UNITINS**
