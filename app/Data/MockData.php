<?php

namespace App\Data;

class MockData
{
    public static function servicos(): array
    {
        return [
            ['id' => 1, 'nome' => 'Corte Masculino', 'preco' => 45.00, 'duracao' => 30],
            ['id' => 2, 'nome' => 'Barba Completa', 'preco' => 35.00, 'duracao' => 25],
            ['id' => 3, 'nome' => 'Corte + Barba', 'preco' => 70.00, 'duracao' => 50],
            ['id' => 4, 'nome' => 'Degradê Premium', 'preco' => 55.00, 'duracao' => 40],
            ['id' => 5, 'nome' => 'Hidratação Capilar', 'preco' => 40.00, 'duracao' => 35],
        ];
    }

    public static function profissionais(): array
    {
        return [
            ['id' => 1, 'nome' => 'Carlos Silva', 'especialidade' => 'Cortes clássicos'],
            ['id' => 2, 'nome' => 'Rafael Mendes', 'especialidade' => 'Degradê e design'],
            ['id' => 3, 'nome' => 'Lucas Oliveira', 'especialidade' => 'Barba e acabamento'],
        ];
    }

    public static function horariosDisponiveis(): array
    {
        return [
            ['hora' => '09:00', 'disponivel' => true],
            ['hora' => '09:30', 'disponivel' => true],
            ['hora' => '10:00', 'disponivel' => false],
            ['hora' => '10:30', 'disponivel' => true],
            ['hora' => '11:00', 'disponivel' => true],
            ['hora' => '11:30', 'disponivel' => false],
            ['hora' => '14:00', 'disponivel' => true],
            ['hora' => '14:30', 'disponivel' => true],
            ['hora' => '15:00', 'disponivel' => true],
            ['hora' => '15:30', 'disponivel' => false],
            ['hora' => '16:00', 'disponivel' => true],
            ['hora' => '17:00', 'disponivel' => true],
        ];
    }

    public static function agendamentosCliente(): array
    {
        return [
            [
                'id' => 101,
                'cliente' => 'João Pedro',
                'servico' => 'Corte + Barba',
                'profissional' => 'Carlos Silva',
                'profissional_id' => 1,
                'data' => '2026-05-25',
                'hora' => '14:30',
                'status' => 'confirmado',
                'preco' => 70.00,
                'observacoes' => 'Preferência por degradê baixo nas laterais.',
            ],
            [
                'id' => 102,
                'cliente' => 'Maria Santos',
                'servico' => 'Degradê Premium',
                'profissional' => 'Rafael Mendes',
                'profissional_id' => 2,
                'data' => '2026-05-28',
                'hora' => '10:00',
                'status' => 'pendente',
                'preco' => 55.00,
                'observacoes' => 'Primeira visita — chegar 10 min antes.',
            ],
            [
                'id' => 103,
                'cliente' => 'Marcos Silva',
                'servico' => 'Barba Completa',
                'profissional' => 'Lucas Oliveira',
                'profissional_id' => 3,
                'data' => '2026-05-20',
                'hora' => '16:00',
                'status' => 'cancelado',
                'preco' => 35.00,
                'observacoes' => 'Cancelado pelo cliente.',
            ],
        ];
    }

    public static function agendaProfissional(string $data = '2026-05-23'): array
    {
        return [
            'data' => $data,
            'data_formatada' => 'Sábado, 23 de Maio de 2026',
            'slots' => [
                ['hora' => '08:00', 'status' => 'livre', 'cliente' => null, 'servico' => null],
                ['hora' => '08:30', 'status' => 'livre', 'cliente' => null, 'servico' => null],
                ['hora' => '09:00', 'status' => 'ocupado', 'cliente' => 'João Pedro', 'servico' => 'Corte Masculino', 'agendamento_status' => 'confirmado'],
                ['hora' => '09:30', 'status' => 'ocupado', 'cliente' => 'Maria Santos', 'servico' => 'Corte + Barba', 'agendamento_status' => 'pendente'],
                ['hora' => '10:00', 'status' => 'livre', 'cliente' => null, 'servico' => null],
                ['hora' => '10:30', 'status' => 'ocupado', 'cliente' => 'André Costa', 'servico' => 'Degradê Premium', 'agendamento_status' => 'confirmado'],
                ['hora' => '11:00', 'status' => 'livre', 'cliente' => null, 'servico' => null],
                ['hora' => '11:30', 'status' => 'livre', 'cliente' => null, 'servico' => null],
                ['hora' => '14:00', 'status' => 'ocupado', 'cliente' => 'Felipe Lima', 'servico' => 'Barba Completa', 'agendamento_status' => 'confirmado'],
                ['hora' => '14:30', 'status' => 'livre', 'cliente' => null, 'servico' => null],
                ['hora' => '15:00', 'status' => 'ocupado', 'cliente' => 'Ricardo Alves', 'servico' => 'Corte Masculino', 'agendamento_status' => 'pendente'],
                ['hora' => '16:00', 'status' => 'livre', 'cliente' => null, 'servico' => null],
            ],
        ];
    }

    public static function servicosProfissional(): array
    {
        return [
            ['id' => 1, 'nome' => 'Corte Masculino', 'preco' => 45.00, 'duracao' => 30],
            ['id' => 2, 'nome' => 'Barba Completa', 'preco' => 35.00, 'duracao' => 25],
            ['id' => 3, 'nome' => 'Corte + Barba', 'preco' => 70.00, 'duracao' => 50],
        ];
    }

    public static function dashboardStats(): array
    {
        return [
            'usuarios' => 1248,
            'agendamentos' => 3842,
            'agendamentos_hoje' => 47,
            'taxa_confirmacao' => 87,
            'pendentes' => 12,
            'taxa_ocupacao' => 74,
            'servico_top' => 'Corte + Barba',
            'profissional_top' => 'Carlos Silva',
            'usuarios_variacao' => '+12%',
            'agendamentos_variacao' => '+8%',
        ];
    }

    public static function atividadesRecentes(): array
    {
        return [
            ['tipo' => 'agendamento', 'descricao' => 'Novo agendamento — Corte + Barba', 'usuario' => 'João Pedro', 'tempo' => 'há 5 min'],
            ['tipo' => 'usuario', 'descricao' => 'Novo cadastro de cliente', 'usuario' => 'Ana Beatriz', 'tempo' => 'há 12 min'],
            ['tipo' => 'cancelamento', 'descricao' => 'Agendamento cancelado', 'usuario' => 'Marcos Silva', 'tempo' => 'há 28 min'],
            ['tipo' => 'agendamento', 'descricao' => 'Agendamento confirmado', 'usuario' => 'Pedro Henrique', 'tempo' => 'há 45 min'],
            ['tipo' => 'sistema', 'descricao' => 'Backup automático concluído', 'usuario' => 'Sistema', 'tempo' => 'há 1 h'],
        ];
    }

    public static function usuarios(): array
    {
        return [
            ['id' => 1, 'nome' => 'João Pedro', 'email' => 'joao@email.com', 'telefone' => '(11) 99999-1111', 'perfil' => 'Cliente', 'ativo' => true, 'cadastro' => '15/03/2026'],
            ['id' => 2, 'nome' => 'Carlos Silva', 'email' => 'carlos@barbearia.com', 'telefone' => '(11) 98888-2222', 'perfil' => 'Profissional', 'ativo' => true, 'cadastro' => '10/01/2026'],
            ['id' => 3, 'nome' => 'Ana Beatriz', 'email' => 'ana@email.com', 'telefone' => '(11) 97777-3333', 'perfil' => 'Cliente', 'ativo' => true, 'cadastro' => '20/05/2026'],
            ['id' => 4, 'nome' => 'Marcos Oliveira', 'email' => 'marcos@email.com', 'telefone' => '(11) 96666-4444', 'perfil' => 'Cliente', 'ativo' => false, 'cadastro' => '05/02/2026'],
            ['id' => 5, 'nome' => 'Admin Sistema', 'email' => 'admin@corteclick.com', 'telefone' => '(11) 95555-5555', 'perfil' => 'Admin', 'ativo' => true, 'cadastro' => '01/01/2026'],
            ['id' => 6, 'nome' => 'Rafael Mendes', 'email' => 'rafael@barbearia.com', 'telefone' => '(11) 94444-6666', 'perfil' => 'Profissional', 'ativo' => true, 'cadastro' => '12/02/2026'],
        ];
    }

    public static function agendamentosAdmin(): array
    {
        return [
            ['id' => 201, 'cliente' => 'João Pedro', 'profissional' => 'Carlos Silva', 'servico' => 'Corte + Barba', 'data' => '2026-05-25', 'hora' => '14:30', 'status' => 'confirmado', 'valor' => 70.00],
            ['id' => 202, 'cliente' => 'Maria Santos', 'profissional' => 'Rafael Mendes', 'servico' => 'Degradê Premium', 'data' => '2026-05-25', 'hora' => '10:00', 'status' => 'pendente', 'valor' => 55.00],
            ['id' => 203, 'cliente' => 'André Costa', 'profissional' => 'Carlos Silva', 'servico' => 'Corte Masculino', 'data' => '2026-05-24', 'hora' => '09:00', 'status' => 'confirmado', 'valor' => 45.00],
            ['id' => 204, 'cliente' => 'Felipe Lima', 'profissional' => 'Lucas Oliveira', 'servico' => 'Barba Completa', 'data' => '2026-05-23', 'hora' => '14:00', 'status' => 'confirmado', 'valor' => 35.00],
            ['id' => 205, 'cliente' => 'Marcos Silva', 'profissional' => 'Rafael Mendes', 'servico' => 'Corte Masculino', 'data' => '2026-05-22', 'hora' => '16:00', 'status' => 'cancelado', 'valor' => 45.00],
        ];
    }

    public static function clienteDashboardStats(): array
    {
        return [
            'proximos' => 2,
            'concluidos' => 12,
            'favorito' => 'Corte + Barba',
        ];
    }

    public static function profissionalDashboardStats(): array
    {
        return [
            'hoje' => 6,
            'confirmados' => 4,
            'pendentes' => 2,
            'livres' => 8,
        ];
    }

    public static function perfis(): array
    {
        return [
            [
                'slug' => 'cliente',
                'titulo' => 'Cliente',
                'descricao' => 'Agende serviços e acompanhe seus horários',
                'rota' => 'cliente.dashboard',
                'icone' => 'user',
            ],
            [
                'slug' => 'profissional',
                'titulo' => 'Profissional',
                'descricao' => 'Gerencie sua agenda e serviços oferecidos',
                'rota' => 'profissional.dashboard',
                'icone' => 'scissors',
            ],
            [
                'slug' => 'admin',
                'titulo' => 'Administrador',
                'descricao' => 'Painel completo de gestão do sistema',
                'rota' => 'admin.dashboard',
                'icone' => 'shield',
            ],
        ];
    }
}
