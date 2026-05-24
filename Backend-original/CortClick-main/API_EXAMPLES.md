# 📡 Exemplos de Requisições da API CorteClick

Este arquivo contém exemplos de requisições para testar a API do CorteClick usando cURL ou Postman.

## 🔐 Autenticação

### Registrar Novo Usuário (Cliente)
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva",
    "email": "joao@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "11999999999",
    "role": "client"
  }'
```

### Registrar Novo Profissional
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Maria Barbeira",
    "email": "maria@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "11988888888",
    "role": "professional"
  }'
```

### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "joao@example.com",
    "password": "password123"
  }'
```

**Resposta:**
```json
{
  "status": "success",
  "message": "Login realizado com sucesso",
  "data": {
    "user": {
      "id": 1,
      "name": "João Silva",
      "email": "joao@example.com",
      "role": "client"
    },
    "token": "1|abc123xyz456..."
  }
}
```

**Copie o token para usar nas próximas requisições!**

### Logout
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer {seu_token_aqui}"
```

### Obter Perfil
```bash
curl -X GET http://localhost:8000/api/profile \
  -H "Authorization: Bearer {seu_token_aqui}"
```

---

## 👤 Usuários

### Listar Usuários (Apenas Admin)
```bash
curl -X GET http://localhost:8000/api/users?page=1 \
  -H "Authorization: Bearer {admin_token}"
```

### Obter Usuário Específico
```bash
curl -X GET http://localhost:8000/api/users/1 \
  -H "Authorization: Bearer {seu_token_aqui}"
```

### Atualizar Usuário
```bash
curl -X PUT http://localhost:8000/api/users/1 \
  -H "Authorization: Bearer {seu_token_aqui}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva Santos",
    "phone": "11998888888"
  }'
```

### Buscar Usuários
```bash
curl -X GET "http://localhost:8000/api/users/search?q=joao" \
  -H "Authorization: Bearer {seu_token_aqui}"
```

### Deletar Usuário
```bash
curl -X DELETE http://localhost:8000/api/users/1 \
  -H "Authorization: Bearer {seu_token_aqui}"
```

---

## 👨‍💼 Profissionais

### Listar Profissionais (Público)
```bash
curl -X GET "http://localhost:8000/api/professionals?page=1&specialty=Corte"
```

### Obter Detalhes do Profissional
```bash
curl -X GET http://localhost:8000/api/professionals/1
```

### Criar Perfil Profissional
```bash
curl -X POST http://localhost:8000/api/professionals \
  -H "Authorization: Bearer {professional_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "specialty": "Corte de Cabelo",
    "description": "Profissional com 10 anos de experiência em cortes modernos",
    "phone": "11999999999",
    "start_time": "09:00",
    "end_time": "18:00",
    "available_days": ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday"]
  }'
```

### Atualizar Profissional
```bash
curl -X PUT http://localhost:8000/api/professionals/1 \
  -H "Authorization: Bearer {professional_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "specialty": "Barba e Corte de Cabelo",
    "end_time": "19:00"
  }'
```

### Deletar Profissional
```bash
curl -X DELETE http://localhost:8000/api/professionals/1 \
  -H "Authorization: Bearer {professional_token}"
```

---

## 🔧 Serviços

### Listar Serviços (Público)
```bash
curl -X GET "http://localhost:8000/api/services?page=1&active=true"
```

### Obter Detalhes do Serviço
```bash
curl -X GET http://localhost:8000/api/services/1
```

### Criar Serviço
```bash
curl -X POST http://localhost:8000/api/services \
  -H "Authorization: Bearer {professional_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Corte de Cabelo",
    "price": 50.00,
    "duration": 30,
    "description": "Corte básico com secagem",
    "is_active": true
  }'
```

### Listar Serviços de um Profissional
```bash
curl -X GET "http://localhost:8000/api/professionals/1/services"
```

### Atualizar Serviço
```bash
curl -X PUT http://localhost:8000/api/services/1 \
  -H "Authorization: Bearer {professional_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "price": 55.00,
    "duration": 45
  }'
```

### Deletar Serviço
```bash
curl -X DELETE http://localhost:8000/api/services/1 \
  -H "Authorization: Bearer {professional_token}"
```

---

## 📅 Agendamentos

### Listar Meus Agendamentos
```bash
curl -X GET "http://localhost:8000/api/appointments?page=1&status=pending" \
  -H "Authorization: Bearer {seu_token_aqui}"
```

### Filtrar por Data
```bash
curl -X GET "http://localhost:8000/api/appointments?from_date=2026-01-15&to_date=2026-02-15" \
  -H "Authorization: Bearer {seu_token_aqui}"
```

### Obter Detalhes do Agendamento
```bash
curl -X GET http://localhost:8000/api/appointments/1 \
  -H "Authorization: Bearer {seu_token_aqui}"
```

### Criar Agendamento
```bash
curl -X POST http://localhost:8000/api/appointments \
  -H "Authorization: Bearer {client_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "professional_id": 1,
    "service_id": 1,
    "appointment_date": "2026-01-20 14:30",
    "notes": "Cortar cabelo curto"
  }'
```

### Atualizar Data do Agendamento
```bash
curl -X PUT http://localhost:8000/api/appointments/1 \
  -H "Authorization: Bearer {client_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "appointment_date": "2026-01-21 15:00"
  }'
```

### Confirmar Agendamento (Profissional)
```bash
curl -X PUT http://localhost:8000/api/appointments/1/status \
  -H "Authorization: Bearer {professional_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "confirmed"
  }'
```

### Marcar como Concluído (Profissional)
```bash
curl -X PUT http://localhost:8000/api/appointments/1/status \
  -H "Authorization: Bearer {professional_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "completed"
  }'
```

### Cancelar Agendamento
```bash
curl -X PUT http://localhost:8000/api/appointments/1/cancel \
  -H "Authorization: Bearer {seu_token_aqui}"
```

**Status disponíveis:** `pending`, `confirmed`, `completed`, `cancelled`

### Deletar Agendamento
```bash
curl -X DELETE http://localhost:8000/api/appointments/1 \
  -H "Authorization: Bearer {client_token}"
```

---

## 🕐 Disponibilidade

### Obter Horários Disponíveis (Um Dia)
```bash
curl -X GET "http://localhost:8000/api/availability/slots/1?date=2026-01-20&service_id=1&duration=30"
```

**Resposta:**
```json
{
  "status": "success",
  "data": {
    "professional_id": 1,
    "date": "2026-01-20",
    "service_id": 1,
    "duration": 30,
    "available_slots": [
      "09:00",
      "09:30",
      "10:00",
      "10:30",
      "14:00",
      "14:30",
      "15:00"
    ]
  }
}
```

### Obter Horários Disponíveis (Intervalo)
```bash
curl -X GET "http://localhost:8000/api/availability/range/1?from_date=2026-01-20&to_date=2026-01-25&service_id=1&duration=30"
```

---

## 💡 Dicas

### Usando Variáveis no cURL
```bash
TOKEN="seu_token_aqui"

curl -X GET http://localhost:8000/api/profile \
  -H "Authorization: Bearer $TOKEN"
```

### Salvando Token em Arquivo
```bash
# Fazer login e salvar token
TOKEN=$(curl -s -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"joao@example.com","password":"password123"}' | jq -r '.data.token')

echo $TOKEN  # Exibe o token
```

### Usando com Postman
1. Configure uma variável de ambiente: `token`
2. Após login, use o script: `pm.environment.set("token", pm.response.json().data.token);`
3. Nas requisições autenticadas, use: `Authorization: Bearer {{token}}`

---

## ⚠️ Códigos de Status HTTP

- `200 OK`: Requisição bem-sucedida
- `201 Created`: Recurso criado com sucesso
- `400 Bad Request`: Erro na requisição
- `401 Unauthorized`: Token ausente ou inválido
- `403 Forbidden`: Sem permissão
- `404 Not Found`: Recurso não encontrado
- `422 Unprocessable Entity`: Erro de validação
- `500 Internal Server Error`: Erro no servidor

---

**Desenvolvido com ❤️ pela turma de TADS - UNITINS**
