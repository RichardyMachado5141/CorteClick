<x-layouts.app
    role="profissional"
    user-name="Profissional"
    title="Dashboard — CorteClick"
    header-title="Dashboard"
    header-subtitle="Sua agenda do dia"
    page="profissional-dashboard"
    :page-data="$pageData"
>
    <div id="profissional-dashboard">
        <div class="mb-6 cc-card border-wine/10 bg-gradient-to-r from-wine-light/60 to-white p-5 animate-fade-in">
            <p class="text-sm text-ink-muted">Painel do profissional</p>
            <h2 data-welcome class="mt-1 text-xl font-bold text-ink">Bem-vindo</h2>
        </div>

        <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="cc-card p-6">
                <p class="text-sm font-medium text-ink-muted">Agendamentos hoje</p>
                <p id="stat-hoje" class="mt-2 text-3xl font-bold text-ink">0</p>
            </div>
            <div class="cc-card p-6">
                <p class="text-sm font-medium text-ink-muted">Confirmados</p>
                <p id="stat-confirmados" class="mt-2 text-3xl font-bold text-emerald-600">0</p>
            </div>
            <div class="cc-card p-6">
                <p class="text-sm font-medium text-ink-muted">Pendentes</p>
                <p id="stat-pendentes" class="mt-2 text-3xl font-bold text-amber-600">0</p>
            </div>
            <div class="cc-card p-6">
                <p class="text-sm font-medium text-ink-muted">Horários livres</p>
                <p id="stat-livres" class="mt-2 text-3xl font-bold text-wine">0</p>
            </div>
        </div>

        <x-card>
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-ink">Agenda do dia</h2>
                    <p id="data-label" class="text-sm text-ink-muted capitalize"></p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" id="btn-prev" class="cc-btn-secondary !px-3 !py-2">
                        <x-icon name="chevron-left" class="h-5 w-5" />
                    </button>
                    <input id="filtro-data-agenda" type="date" class="cc-input !w-auto" value="{{ date('Y-m-d') }}" />
                    <button type="button" id="btn-next" class="cc-btn-secondary !px-3 !py-2">
                        <x-icon name="chevron-right" class="h-5 w-5" />
                    </button>
                </div>
            </div>

            <div class="mb-4 flex flex-wrap gap-4 text-sm">
                <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Livre</span>
                <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span> Pendente</span>
                <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-wine"></span> Confirmado</span>
            </div>

            <div id="agenda-empty" class="hidden rounded-xl border border-dashed border-border bg-gray-50 py-16 text-center">
                <x-icon name="calendar" class="mx-auto h-12 w-12 text-ink-light" />
                <p class="mt-3 font-medium text-ink">Nenhum agendamento neste dia</p>
                <p class="mt-1 text-sm text-ink-muted">Os horários solicitados pelos clientes aparecerão aqui</p>
            </div>

            <div id="agenda-grid" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3"></div>
        </x-card>
    </div>
</x-layouts.app>
