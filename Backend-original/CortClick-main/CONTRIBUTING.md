# 🤝 Guia de Contribuição - CorteClick

Obrigado por considerar contribuir para o **CorteClick**! Este documento fornece diretrizes e instruções para ajudar no desenvolvimento.

## 📋 Antes de Começar

1. Leia [README.md](README.md)
2. Entenda a [estrutura do projeto](WELCOME.md)
3. Configure o ambiente seguindo [SETUP.md](SETUP.md)
4. Familiarize-se com os [endpoints](API_EXAMPLES.md)

## 🎯 Como Contribuir

### 1. Encontrando Issues

- Verifique issues abertas no repositório
- Propose novas funcionalidades via issues
- Reporte bugs com detalhes claros

### 2. Fork e Clone

```bash
git clone https://github.com/seu-usuario/cortclick.git
cd cortclick
git checkout -b feature/sua-funcionalidade
```

### 3. Desenvolvimento

#### Adicionando um Novo Endpoint

**1. Criar Migration (se necessário)**
```bash
php artisan make:migration create_xxx_table
```

**2. Criar ou Atualizar Model**
```bash
php artisan make:model Xxx
```

**3. Criar Controller**
```bash
php artisan make:controller Api/XxxController --resource
```

**4. Adicionar Rotas em `routes/api.php`**
```php
Route::apiResource('xxx', Api\XxxController::class);
```

**5. Implementar Lógica**
```php
// Em app/Http/Controllers/Api/XxxController.php
public function index() { ... }
public function store(Request $request) { ... }
public function show($id) { ... }
public function update(Request $request, $id) { ... }
public function destroy($id) { ... }
```

#### Exemplo Completo: Adicionando "Avaliações"

**1. Migration: `database/migrations/*_create_ratings_table.php`**
```php
Schema::create('ratings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
    $table->integer('rating'); // 1-5
    $table->text('comment')->nullable();
    $table->timestamps();
});
```

**2. Model: `app/Models/Rating.php`**
```php
class Rating extends Model {
    protected $fillable = ['appointment_id', 'rating', 'comment'];
    
    public function appointment() {
        return $this->belongsTo(Appointment::class);
    }
}
```

**3. Controller: `app/Http/Controllers/Api/RatingController.php`**
```php
class RatingController extends Controller {
    public function store(Request $request) {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string'
        ]);
        
        $rating = Rating::create($validated);
        return response()->json(['status' => 'success', 'data' => $rating], 201);
    }
}
```

**4. Routes: `routes/api.php`**
```php
Route::apiResource('ratings', Api\RatingController::class)->middleware('auth:sanctum');
```

**5. Migrate**
```bash
php artisan migrate
```

### 4. Testes

```bash
# Executar testes
php artisan test

# Com cobertura
php artisan test --coverage
```

### 5. Padrões de Código

#### Controllers
```php
class XxxController extends Controller {
    // Sempre validar entrada
    $validated = $request->validate([...]);
    
    // Sempre retornar JSON
    return response()->json([
        'status' => 'success|error',
        'message' => 'Descrição',
        'data' => $data
    ], 200); // HTTP status
    
    // Sempre logar ações
    $this->logUserAction($user, 'action', 'Model', $id);
}
```

#### Models
```php
class Xxx extends Model {
    use HasFactory, SoftDeletes; // Quando apropriado
    
    #[Fillable(['field1', 'field2'])]
    
    // Sempre definir relacionamentos
    public function related() { return $this->belongsTo(...); }
    
    // Usar escopos para queries comuns
    public function scopeActive($query) { 
        return $query->where('is_active', true); 
    }
}
```

#### Validação
```php
// SEMPRE validar entrada
$validated = $request->validate([
    'email' => 'required|email|unique:users,email',
    'password' => 'required|string|min:8',
    'role' => 'required|in:client,professional,admin',
]);
```

#### Respostas
```php
// Success
return response()->json([
    'status' => 'success',
    'message' => 'Recurso criado com sucesso',
    'data' => $resource
], 201);

// Error
return response()->json([
    'status' => 'error',
    'message' => 'Algo deu errado',
    'error' => $e->getMessage()
], 400);
```

### 6. Convenções de Nomenclatura

| Item | Convenção | Exemplo |
|------|-----------|---------|
| Controllers | PascalCase | `UserController` |
| Models | PascalCase | `User` |
| Methods | camelCase | `getUserById()` |
| Variables | camelCase | `$userName` |
| Tables | snake_case | `user_logs` |
| Columns | snake_case | `created_at` |
| Routes | kebab-case | `/api/user-logs` |

### 7. Commit Messages

```bash
git commit -m "feat: adicionar sistema de avaliações"
git commit -m "fix: corrigir conflito de agendamento"
git commit -m "docs: atualizar documentação da API"
git commit -m "refactor: limpar código do controller"
```

**Tipos:**
- `feat:` Nova funcionalidade
- `fix:` Correção de bug
- `docs:` Documentação
- `style:` Formatação
- `refactor:` Refatoração
- `test:` Testes
- `chore:` Tarefas gerais

### 8. Push e Pull Request

```bash
git push origin feature/sua-funcionalidade
```

1. Vá para GitHub
2. Clique "Create Pull Request"
3. Descreva as mudanças
4. Aguarde review

### 9. Documente Tudo

- Atualize README.md se necessário
- Adicione exemplos em API_EXAMPLES.md
- Adicione comentários no código
- Atualize IMPLEMENTATION.md

## ✅ Checklist Antes de Submeter

- [ ] Código segue padrões do projeto
- [ ] Validação implementada
- [ ] Erros tratados
- [ ] Logs implementados (se necessário)
- [ ] Testes escritos
- [ ] Documentação atualizada
- [ ] Sem warnings/errors
- [ ] Testado localmente

## 🐛 Reportando Bugs

**Template:**
```
Descrição: [descrição clara do bug]
Passos para reproduzir:
1. [passo 1]
2. [passo 2]
Resultado esperado: [o que deveria acontecer]
Resultado atual: [o que realmente acontece]
Versão: [Laravel 13.8, PHP 8.3, MySQL 5.7]
```

## 💡 Sugerindo Funcionalidades

**Template:**
```
Descrição: [descrição clara da funcionalidade]
Benefícios: [por que é útil]
Exemplo de uso: [como seria usado]
Requisitos funcionais relacionados: [RF/RNF]
```

## 📚 Recursos Úteis

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Best Practices](https://laravel.com/docs/readme)
- [RESTful API Design](https://restfulapi.net)
- [PHP-FIG PSR Standards](https://www.php-fig.org)

## 🚀 Melhorias Futuras Sugeridas

### Curto Prazo (Fácil)
- [ ] Implementar paginação em todas as listagens
- [ ] Adicionar filtros avançados
- [ ] Implementar ordenação por diferentes campos
- [ ] Adicionar sistema de avaliações/reviews

### Médio Prazo (Moderado)
- [ ] Implementar testes automatizados (PHPUnit)
- [ ] Adicionar notificações por email
- [ ] Implementar sistema de promoções/cupons
- [ ] Adicionar relatórios e estatísticas

### Longo Prazo (Desafiador)
- [ ] Desenvolver frontend com Vue.js/React
- [ ] Criar aplicativo mobile com React Native
- [ ] Implementar pagamentos com Stripe/PayPal
- [ ] Adicionar chat em tempo real
- [ ] Implementar recomendações com IA
- [ ] Deploy em servidor de produção com CI/CD

## 📞 Dúvidas?

- Abra uma issue no GitHub
- Consulte a documentação do projeto
- Envie email para o mantenedor

## 📄 Licença

Ao contribuir, você concorda que suas contribuições serão licenciadas sob a MIT License.

---

**Obrigado por contribuir! 🎉**
**Desenvolvido com ❤️ pela turma de TADS - UNITINS**
