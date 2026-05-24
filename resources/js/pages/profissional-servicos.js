import { Toast, Loading, Modal, formatCurrency, delay } from '../core/ui.js';
import { Store } from '../core/store.js';
import { $id, $in, $$in } from '../core/dom.js';

export function initProfissionalServicos(initial) {
    const store = new Store(initial);
    const root = document.getElementById('profissional-servicos');
    if (!root) return;

    let editingId = null;

    const form = $id('form-servico');
    const titleEl = $id('modal-servico-title');
    const inputNome = $id('form-nome');
    const inputPreco = $id('form-preco');
    const inputDuracao = $id('form-duracao');

    function openFormModal(isEdit, servico = null) {
        editingId = isEdit ? servico?.id ?? null : null;
        if (titleEl) titleEl.textContent = isEdit ? 'Editar serviço' : 'Adicionar serviço';
        if (inputNome) inputNome.value = servico?.nome ?? '';
        if (inputPreco) inputPreco.value = servico?.preco ?? '';
        if (inputDuracao) inputDuracao.value = servico?.duracao ?? '';
        Modal.open('modal-servico');
        setTimeout(() => inputNome?.focus(), 250);
    }

    function render() {
        const grid = $in(root, '#servicos-grid');
        if (!grid) return;

        if (!store.data.servicos.length) {
            grid.innerHTML = `
                <div class="col-span-full cc-card py-16 text-center">
                    <p class="font-medium text-ink">Nenhum serviço cadastrado</p>
                    <button type="button" id="btn-novo-empty" class="cc-btn-primary mt-4">Adicionar primeiro serviço</button>
                </div>
            `;
            $id('btn-novo-empty')?.addEventListener('click', () => openFormModal(false));
            return;
        }

        grid.innerHTML = store.data.servicos.map((s) => `
            <article class="cc-card-hover p-5 animate-fade-in" data-id="${s.id}">
                <h3 class="text-lg font-semibold text-ink">${s.nome}</h3>
                <p class="mt-2 text-2xl font-bold text-wine">${formatCurrency(s.preco)}</p>
                <p class="mt-1 flex items-center gap-1 text-sm text-ink-muted">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    ${s.duracao} min
                </p>
                <div class="mt-5 flex gap-2 border-t border-border pt-4">
                    <button type="button" data-editar="${s.id}" class="cc-btn-secondary flex-1 !py-2">Editar</button>
                    <button type="button" data-excluir="${s.id}" class="cc-btn-danger !py-2">Excluir</button>
                </div>
            </article>
        `).join('');

        $$in(grid, '[data-editar]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const s = store.data.servicos.find((x) => x.id === Number(btn.dataset.editar));
                if (s) openFormModal(true, s);
            });
        });

        $$in(grid, '[data-excluir]').forEach((btn) => {
            btn.addEventListener('click', async () => {
                const id = Number(btn.dataset.excluir);
                const s = store.data.servicos.find((x) => x.id === id);
                if (!s || !confirm(`Excluir o serviço "${s.nome}"?`)) return;

                await Loading.wrap(delay(400), 'Excluindo serviço...');
                store.data.servicos = store.data.servicos.filter((x) => x.id !== id);
                store.save();
                render();
                Toast.show('Serviço removido com sucesso', 'info');
            });
        });
    }

    $in(root, '#btn-novo')?.addEventListener('click', () => openFormModal(false));

    form?.addEventListener('submit', async (e) => {
        e.preventDefault();

        const nome = inputNome?.value.trim() ?? '';
        const preco = parseFloat(inputPreco?.value ?? '');
        const duracao = parseInt(inputDuracao?.value ?? '', 10);

        if (!nome || isNaN(preco) || preco < 0 || isNaN(duracao) || duracao < 5) {
            Toast.show('Preencha nome, preço e duração (mín. 5 min)', 'warning');
            return;
        }

        await Loading.wrap(
            delay(500),
            editingId ? 'Salvando alterações...' : 'Adicionando serviço...',
        );

        if (editingId) {
            const s = store.data.servicos.find((x) => x.id === editingId);
            if (s) Object.assign(s, { nome, preco, duracao });
            Toast.show('Serviço atualizado com sucesso!', 'success');
        } else {
            store.data.servicos.push({ id: Date.now(), nome, preco, duracao });
            Toast.show('Serviço adicionado com sucesso!', 'success');
        }

        store.save();
        Modal.close('modal-servico');
        editingId = null;
        render();
    });

    render();
}
