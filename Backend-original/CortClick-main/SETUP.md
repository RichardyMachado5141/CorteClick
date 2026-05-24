# 🚀 Guia Rápido de Setup - CorteClick

## Instalação Rápida (5 minutos)

### Pré-requisitos
- PHP 8.3+
- MySQL/MariaDB
- Composer
- Git

### Passos

#### 1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/cortclick.git
cd cortclick
```

#### 2. Instale dependências
```bash
composer install
npm install
```

#### 3. Configure o ambiente
```bash
cp .env.example .env
php artisan key:generate
```

**Edite o `.env` com suas credenciais do banco:**
```env
DB_HOST=127.0.0.1
DB_DATABASE=corteClick
DB_USERNAME=root
DB_PASSWORD=sua_senha_aqui
```

#### 4. Crie o banco e execute migrações
```bash
php artisan migrate
php artisan db:seed
```

#### 5. Inicie o servidor
```bash
php artisan serve
```

**Pronto!** 🎉 Acesse em `http://localhost:8000`

---

## Dados de Teste

Após executar `php artisan db:seed`, você terá:

### Admin
- Email: `admin@corteclick.com`
- Senha: `password` (gerada automaticamente)

### Clientes (5 usuários)
- Emails aleatórios com role `client`

### Profissionais (3 usuários)
- Emails aleatórios com role `professional`
- Cada um com 3 serviços

### Agendamentos (10 agendamentos)
- Distribuídos entre clientes e profissionais

---

## Testes da API

### Com Postman

1. Abra o Postman
2. Importe a collection (se disponível)
3. Configure a variável `{{base_url}}` como `http://localhost:8000/api`

### Com cURL

#### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@corteclick.com",
    "password": "password"
  }'
```

#### Listar Profissionais
```bash
curl -X GET http://localhost:8000/api/professionals
```

#### Criar Agendamento (autenticado)
```bash
curl -X POST http://localhost:8000/api/appointments \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {seu_token_aqui}" \
  -d '{
    "professional_id": 1,
    "service_id": 1,
    "appointment_date": "2026-01-20 14:30",
    "notes": "Corte desejado"
  }'
```

---

## Solução de Problemas

### Erro: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Erro: "SQLSTATE[HY000]: General error: 1366"
Certifique-se que o banco suporta UTF-8:
```bash
ALTER DATABASE corteClick CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Erro de permissão de arquivo
```bash
chmod -R 775 storage bootstrap/cache
```

---

## Estrutura de Pastas Importante

```
app/
├── Http/Controllers/Api/     # Controllers da API
├── Models/                    # Modelos Eloquent
routes/
├── api.php                    # Rotas da API
database/
├── migrations/                # Migrações do banco
├── seeders/                   # Seeds com dados de teste
└── database.sql               # SQL completo do banco
```

---

## Próximos Passos

1. Customize os dados de teste em `database/seeders/DatabaseSeeder.php`
2. Implemente autenticação no frontend
3. Configure CORS em `config/cors.php` para produção
4. Configure rate limiting em `routes/api.php`
5. Implemente testes em `tests/`

---

## Documentação Completa

Veja [README.md](README.md) para documentação completa da API e funcionalidades.

---

**Desenvolvido com ❤️ pela turma de TADS - UNITINS**
