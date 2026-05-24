<x-layouts.app
    role="profissional"
    user-name="Profissional"
    title="Meus Serviços — CorteClick"
    header-title="Meus Serviços"
    header-subtitle="Gerencie os serviços oferecidos"
    page="profissional-servicos"
    :page-data="$pageData"
>
    <div id="profissional-servicos">
        <div class="mb-6 flex justify-end">
            <button type="button" id="btn-novo" class="cc-btn-primary transition hover:shadow-md">
                <x-icon name="plus" class="h-4 w-4" />
                Adicionar serviço
            </button>
        </div>

        <div id="servicos-grid" class="grid gap-4 md:grid-cols-2 xl:grid-cols-3"></div>
    </div>

    <x-modal id="modal-servico" size="md">
        <h3 id="modal-servico-title" class="mb-5 text-lg font-semibold text-ink">Adicionar serviço</h3>
        <form id="form-servico" class="space-y-4" novalidate>
            <div class="space-y-1.5">
                <label for="form-nome" class="text-sm font-medium text-ink">Nome do serviço</label>
                <input id="form-nome" type="text" class="cc-input" placeholder="Ex: Corte Masculino" required autocomplete="off" />
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-1.5">
                    <label for="form-preco" class="text-sm font-medium text-ink">Preço (R$)</label>
                    <input id="form-preco" type="number" step="0.01" min="0" class="cc-input" placeholder="45.00" required />
                </div>
                <div class="space-y-1.5">
                    <label for="form-duracao" class="text-sm font-medium text-ink">Duração (min)</label>
                    <input id="form-duracao" type="number" min="5" step="5" class="cc-input" placeholder="30" required />
                </div>
            </div>
            <div class="flex gap-3 border-t border-border pt-4">
                <button type="button" data-modal-close="modal-servico" class="cc-btn-secondary flex-1">Cancelar</button>
                <button type="submit" class="cc-btn-primary flex-1">Salvar</button>
            </div>
        </form>
    </x-modal>
</x-layouts.app>
