<x-layouts.app
    role="admin"
    user-name="Admin Sistema"
    title="Agendamentos — CorteClick"
    header-title="Agendamentos"
    header-subtitle="Gestão geral de agendamentos"
    page="admin-agendamentos"
    :page-data="$pageData"
>
    <div id="admin-agendamentos">
        <x-card class="mb-6">
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-ink">Data</label>
                    <input id="filtro-data" type="date" class="cc-input" />
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-ink">Status</label>
                    <select id="filtro-status" class="cc-input">
                        <option value="">Todos</option>
                        <option value="confirmado">Confirmado</option>
                        <option value="pendente">Pendente</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="button" id="btn-filtrar" class="cc-btn-primary w-full">
                        <x-icon name="search" class="h-4 w-4" />
                        Filtrar
                    </button>
                </div>
            </div>
            <p id="contador" class="mt-3 text-sm text-ink-muted"></p>
            <div class="mt-4 flex flex-wrap gap-2">
                <button type="button" data-filtro="todos" class="rounded-full bg-wine px-4 py-1.5 text-sm font-medium text-white shadow-sm transition">Todos</button>
                <button type="button" data-filtro="confirmado" class="rounded-full bg-white px-4 py-1.5 text-sm font-medium text-ink-muted shadow-sm transition hover:bg-gray-50 hover:text-ink">Confirmados</button>
                <button type="button" data-filtro="pendente" class="rounded-full bg-white px-4 py-1.5 text-sm font-medium text-ink-muted shadow-sm transition hover:bg-gray-50 hover:text-ink">Pendentes</button>
                <button type="button" data-filtro="cancelado" class="rounded-full bg-white px-4 py-1.5 text-sm font-medium text-ink-muted shadow-sm transition hover:bg-gray-50 hover:text-ink">Cancelados</button>
            </div>
        </x-card>

        <div id="lista-agendamentos" class="space-y-4 transition-opacity duration-200"></div>

        <div id="lista-vazia" class="hidden cc-card py-16 text-center">
            <x-icon name="calendar" class="mx-auto h-12 w-12 text-ink-light" />
            <p class="mt-3 font-medium text-ink">Nenhum agendamento neste filtro</p>
            <p class="mt-1 text-sm text-ink-muted">Tente outro status ou limpe o filtro de data</p>
        </div>
    </div>
</x-layouts.app>
