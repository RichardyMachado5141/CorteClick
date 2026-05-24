<x-layouts.app
    role="cliente"
    user-name="Cliente"
    title="Dashboard — CorteClick"
    header-title="Dashboard"
    header-subtitle="Agende seu próximo horário"
    page="cliente-dashboard"
    :page-data="$pageData"
>
    <div id="cliente-dashboard">
        <div class="mb-6 cc-card border-wine/10 bg-gradient-to-r from-wine-light/60 to-white p-5 animate-fade-in">
            <p class="text-sm text-ink-muted">Painel do cliente</p>
            <h2 data-welcome class="mt-1 text-xl font-bold text-ink">Bem-vindo</h2>
        </div>

        <div class="mb-8 grid gap-4 sm:grid-cols-3">
            <x-stat-card label="Próximos agendamentos" :value="$stats['proximos']" icon="calendar" />
            <x-stat-card label="Concluídos" :value="$stats['concluidos']" icon="clipboard" />
            <div class="cc-card p-6">
                <p class="text-sm font-medium text-ink-muted">Serviço favorito</p>
                <p class="mt-2 text-xl font-bold text-wine">{{ $stats['favorito'] }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-1">
                <x-card>
                    <h2 class="mb-5 text-base font-semibold text-ink">Filtros de busca</h2>
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-ink">Serviço</label>
                            <select id="filtro-servico" class="cc-input">
                                <option value="">Selecione um serviço</option>
                                @foreach($pageData['servicos'] as $s)
                                    <option value="{{ $s['id'] }}">{{ $s['nome'] }} — R$ {{ number_format($s['preco'], 2, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-ink">Profissional</label>
                            <select id="filtro-profissional" class="cc-input">
                                <option value="">Selecione um profissional</option>
                                @foreach($pageData['profissionais'] as $p)
                                    <option value="{{ $p['id'] }}">{{ $p['nome'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-ink">Data</label>
                            <input id="filtro-data" type="date" class="cc-input" value="{{ date('Y-m-d') }}" />
                        </div>
                        <button type="button" id="btn-buscar" class="cc-btn-primary w-full">
                            <x-icon name="search" class="h-4 w-4" />
                            Buscar horários
                        </button>
                        <p class="text-xs text-ink-muted">Horários: 07:00–18:00 · Almoço: 12:00–14:00</p>
                    </div>
                </x-card>
            </div>

            <div class="lg:col-span-2">
                <x-card>
                    <div class="mb-5">
                        <h2 class="text-base font-semibold text-ink">Horários disponíveis</h2>
                        <p id="horarios-summary" class="text-sm text-ink-muted">Selecione serviço e profissional para buscar horários</p>
                    </div>

                    <div id="horarios-empty" class="rounded-xl border border-dashed border-border bg-gray-50 py-12 text-center">
                        <x-icon name="calendar" class="mx-auto h-10 w-10 text-ink-light" />
                        <p class="mt-3 text-sm text-ink-muted">Selecione profissional e data para ver os horários</p>
                    </div>

                    <div id="horarios-weekend" class="hidden rounded-xl border border-amber-200 bg-amber-50 py-12 text-center">
                        <x-icon name="calendar" class="mx-auto h-10 w-10 text-amber-500" />
                        <p class="mt-3 text-base font-semibold text-amber-800">Barbearia fechada aos finais de semana</p>
                        <p class="mt-1 text-sm text-amber-700/80">Atendemos de segunda a sexta, das 07:00 às 18:00</p>
                    </div>

                    <div id="horarios-grid" class="grid grid-cols-3 gap-2 sm:grid-cols-4 md:grid-cols-5"></div>

                    <div class="mt-6 flex flex-col gap-4 border-t border-border pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <p id="selection-hint" class="text-sm text-ink-muted">Selecione um horário para agendar</p>
                        <button type="button" id="btn-agendar" class="cc-btn-primary" disabled>
                            <x-icon name="calendar" class="h-4 w-4" />
                            Solicitar horário
                        </button>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <x-modal id="modal-confirmacao" title="Solicitação enviada!" size="sm">
        <div class="text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9.75 4.5"/></svg>
            </div>
            <div id="confirmacao-detalhes" class="text-sm text-ink"></div>
            <div class="mt-6 flex gap-3">
                <a href="{{ route('cliente.agendamentos') }}" class="cc-btn-primary flex-1">Ver agendamentos</a>
                <button type="button" data-modal-close="modal-confirmacao" class="cc-btn-secondary flex-1">Fechar</button>
            </div>
        </div>
    </x-modal>
</x-layouts.app>
