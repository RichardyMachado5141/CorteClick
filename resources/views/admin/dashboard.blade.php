<x-layouts.app
    role="admin"
    user-name="Administrador"
    title="Dashboard — CorteClick"
    header-title="Dashboard"
    header-subtitle="Visão geral do sistema"
>
    <div class="mb-6 cc-card border-wine/10 bg-gradient-to-r from-wine-light/60 to-white p-5 animate-fade-in">
        <p class="text-sm text-ink-muted">Painel administrativo</p>
        <h2 data-welcome class="mt-1 text-xl font-bold text-ink">Bem-vindo</h2>
    </div>

    <div class="mb-6 cc-card p-5">
        <h3 class="text-sm font-semibold text-ink">Acesso rápido aos painéis</h3>
        <p class="mt-1 text-xs text-ink-muted">Como administrador, você pode visualizar qualquer área sem novo login.</p>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ url('/admin') }}" class="cc-btn-primary">Painel Admin</a>
            <a href="{{ url('/cliente') }}" class="cc-btn-secondary">Painel Cliente</a>
            <a href="{{ url('/profissional') }}" class="cc-btn-secondary">Painel Profissional</a>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="Usuários" :value="number_format($stats['usuarios'], 0, ',', '.')" :variation="$stats['usuarios_variacao']" icon="users" />
        <x-stat-card label="Agendamentos" :value="number_format($stats['agendamentos'], 0, ',', '.')" :variation="$stats['agendamentos_variacao']" icon="clipboard" />
        <x-stat-card label="Hoje" :value="$stats['agendamentos_hoje']" icon="calendar" />
        <x-stat-card label="Taxa de confirmação" :value="$stats['taxa_confirmacao'] . '%'" icon="chart" />
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <x-card class="lg:col-span-2">
            <h2 class="mb-5 text-base font-semibold text-ink">Atividades recentes</h2>
            <ul class="space-y-3">
                @foreach($atividades as $atividade)
                    @php
                        $bg = match($atividade['tipo']) {
                            'agendamento' => 'bg-wine-light text-wine',
                            'usuario' => 'bg-blue-50 text-blue-600',
                            'cancelamento' => 'bg-red-50 text-red-600',
                            default => 'bg-gray-100 text-ink-muted',
                        };
                        $icon = match($atividade['tipo']) {
                            'agendamento', 'cancelamento' => 'calendar',
                            'usuario' => 'user',
                            default => 'chart',
                        };
                    @endphp
                    <li class="flex items-start gap-3 rounded-xl border border-border bg-gray-50/50 p-4 transition hover:bg-white hover:shadow-sm">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $bg }}">
                            <x-icon :name="$icon" class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-ink">{{ $atividade['descricao'] }}</p>
                            <p class="text-xs text-ink-muted">{{ $atividade['usuario'] }} · {{ $atividade['tempo'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </x-card>

        <x-card>
            <h2 class="mb-5 text-base font-semibold text-ink">Monitoramento</h2>
            <div class="space-y-4">
                @php
                    $monitors = [
                        ['Servidor', 100, 'Online', 'text-emerald-600', 'bg-emerald-500'],
                        ['Banco de dados', 92, 'Conectado', 'text-emerald-600', 'bg-emerald-500'],
                        ['Armazenamento', 68, '68%', 'text-amber-600', 'bg-amber-500'],
                        ['API', 100, 'Operacional', 'text-emerald-600', 'bg-emerald-500'],
                    ];
                @endphp
                @foreach($monitors as [$label, $pct, $status, $textClass, $barClass])
                    <div>
                        <div class="mb-1.5 flex justify-between text-sm">
                            <span class="text-ink-muted">{{ $label }}</span>
                            <span class="font-medium {{ $textClass }}">{{ $status }}</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-gray-100">
                            <div class="h-full rounded-full {{ $barClass }} transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-5 space-y-3 rounded-xl bg-wine-light p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-wine">Operação</p>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-ink-muted">Taxa de ocupação</span>
                    <span class="font-semibold text-ink">{{ $stats['taxa_ocupacao'] }}%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-ink-muted">Pendentes</span>
                    <span class="font-semibold text-amber-700">{{ $stats['pendentes'] }}</span>
                </div>
                <div class="border-t border-wine/10 pt-3 text-sm">
                    <p class="text-ink-muted">Serviço mais agendado</p>
                    <p class="mt-0.5 font-medium text-ink">{{ $stats['servico_top'] }}</p>
                </div>
                <div class="text-sm">
                    <p class="text-ink-muted">Profissional mais ativo</p>
                    <p class="mt-0.5 font-medium text-ink">{{ $stats['profissional_top'] }}</p>
                </div>
            </div>
        </x-card>
    </div>
</x-layouts.app>
