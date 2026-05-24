# 📦 Instalação de Dependências Faltantes

## ⚠️ Dependências Necessárias

Para que a API funcione completamente, é necessário instalar o **Laravel Sanctum** para autenticação via tokens.

## 🔧 Instalação do Sanctum

### Passo 1: Instale o Sanctum via Composer

```bash
composer require laravel/sanctum
```

### Passo 2: Publique os arquivos do Sanctum

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### Passo 3: Crie a tabela de tokens

```bash
php artisan migrate
```

### Passo 4: Configure o Sanctum em `config/sanctum.php`

O arquivo de configuração será criado automaticamente. Se precisar customizar:

```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,localhost:3001,127.0.0.1,127.0.0.1:8000,127.0.0.1:3000,127.0.0.1:3001'.
    (env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : '')
))),

'expiration' => null,  // Tokens não expiram por padrão
```

## 📋 Checklist de Instalação

- [ ] Composer instalado
- [ ] PHP 8.3+
- [ ] MySQL/MariaDB
- [ ] Git
- [ ] Node.js 18+
- [ ] Clonar repositório
- [ ] `composer install`
- [ ] `npm install`
- [ ] `cp .env.example .env`
- [ ] `php artisan key:generate`
- [ ] **`composer require laravel/sanctum`**
- [ ] **`php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`**
- [ ] Configurar `.env` (DB_*)
- [ ] `php artisan migrate --seed`
- [ ] `php artisan serve`

## 🔑 Como Funciona a Autenticação

### 1. Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'
```

**Resposta:**
```json
{
  "token": "1|abc123xyz456..."
}
```

### 2. Usar o Token
Inclua o token em todas as requisições autenticadas:

```bash
curl -X GET http://localhost:8000/api/profile \
  -H "Authorization: Bearer 1|abc123xyz456..."
```

### 3. Logout
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer 1|abc123xyz456..."
```

## 🧹 Limpeza Opcional

Se precisar deletar todos os tokens (útil para desenvolvimento):

```bash
# Via SQL
DELETE FROM personal_access_tokens;

# Ou via Artisan
php artisan tinker
>>> App\Models\User::find(1)->tokens()->delete()
```

## 🆘 Solução de Problemas

### Erro: "Class 'Laravel\Sanctum\Sanctum' not found"
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### Erro: "Table 'personal_access_tokens' doesn't exist"
```bash
php artisan migrate
```

### Erro: "No routes matched with those values"
Verifique que o arquivo `routes/api.php` está correto:
```php
Route::middleware('auth:sanctum')->group(function () {
    // Suas rotas autenticadas aqui
});
```

## 📚 Documentação Oficial

- [Laravel Sanctum Docs](https://laravel.com/docs/sanctum)
- [API Authentication](https://laravel.com/docs/authentication#sanctum)

---

**Desenvolvido com ❤️ pela turma de TADS - UNITINS**
