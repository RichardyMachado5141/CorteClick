<x-layouts.app
    role="cliente"
    user-name="Cliente"
    title="Meus Agendamentos — CorteClick"
    header-title="Meus Agendamentos"
    header-subtitle="Gerencie seus horários"
    page="cliente-agendamentos"
    :page-data="$pageData"
>
    <div id="cliente-agendamentos">
        <div class="mb-6 flex flex-wrap gap-2">
            <button type="button" data-filtro="todos" class="rounded-full bg-wine px-4 py-1.5 text-sm font-medium text-white shadow-sm transition">Todos</button>
            <button type="button" data-filtro="confirmado" class="rounded-full bg-white px-4 py-1.5 text-sm font-medium text-ink-muted shadow-sm transition hover:bg-gray-50 hover:text-ink">Confirmados</button>
            <button type="button" data-filtro="pendente" class="rounded-full bg-white px-4 py-1.5 text-sm font-medium text-ink-muted shadow-sm transition hover:bg-gray-50 hover:text-ink">Pendentes</button>
            <button type="button" data-filtro="cancelado" class="rounded-full bg-white px-4 py-1.5 text-sm font-medium text-ink-muted shadow-sm transition hover:bg-gray-50 hover:text-ink">Cancelados</button>
        </div>

        <div id="lista-agendamentos" class="space-y-4"></div>

        <div id="lista-vazia" class="hidden cc-card py-16 text-center">
            <x-icon name="calendar" class="mx-auto h-12 w-12 text-ink-light" />
            <p class="mt-3 font-medium text-ink">Nenhum agendamento encontrado</p>
            <a href="{{ route('cliente.dashboard') }}" class="cc-btn-primary mt-4 inline-flex">Agendar agora</a>
        </div>
    </div>

    <x-modal id="modal-detalhes" title="Detalhes do agendamento" size="lg">
        <div id="modal-detalhes-body"></div>
        <button type="button" data-modal-close="modal-detalhes" class="cc-btn-secondary mt-6 w-full transition hover:shadow-sm">
            Fechar
        </button>
    </x-modal>
</x-layouts.app>
