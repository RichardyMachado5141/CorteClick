import { Toast, Loading, Modal, formatCurrency, formatDate, delay } from '../core/ui.js';
import { Store, badgeHtml } from '../core/store.js';
import { $id, $in, $$in } from '../core/dom.js';

export function initClienteAgendamentos(initial) {
    const store = new Store(initial);
    const root = document.getElementById('cliente-agendamentos');
    if (!root) return;

    let filtroStatus = 'todos';
    const modalBody = $id('modal-detalhes-body');

    function filtrar(lista) {
        if (filtroStatus === 'todos') return lista;
        return lista.filter((a) => a.status === filtroStatus);
    }

    function renderDetalhesModal(item) {
        if (!modalBody) return;

        modalBody.innerHTML = `
            <div class="space-y-5">
                <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3">
                    <span class="text-sm text-ink-muted">Status</span>
                    ${badgeHtml(item.status)}
                </div>
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-border bg-white p-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink-light">Profissional</dt>
                        <dd class="mt-1 font-semibold text-ink">${item.profissional}</dd>
                    </div>
                    <div class="rounded-xl border border-border bg-white p-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink-light">Serviço</dt>
                        <dd class="mt-1 font-semibold text-ink">${item.servico}</dd>
                    </div>
                    <div class="rounded-xl border border-border bg-white p-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink-light">Data</dt>
                        <dd class="mt-1 font-semibold text-ink">${formatDate(item.data)}</dd>
                    </div>
                    <div class="rounded-xl border border-border bg-white p-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink-light">Horário</dt>
                        <dd class="mt-1 font-semibold text-ink">${item.hora}</dd>
                    </div>
                </dl>
                <div class="rounded-xl border border-border bg-wine-light/40 p-4">
                    <dt class="text-xs font-medium uppercase tracking-wide text-wine">Valor</dt>
                    <dd class="mt-1 text-xl font-bold text-wine">${formatCurrency(item.preco)}</dd>
                </div>
                <div class="rounded-xl border border-border bg-gray-50 p-4">
                    <dt class="text-xs font-medium uppercase tracking-wide text-ink-light">Observações</dt>
                    <dd class="mt-2 text-sm leading-relaxed text-ink-muted">${item.observacoes || 'Nenhuma observação registrada.'}</dd>
                </div>
            </div>
        `;
    }

    function render() {
        const lista = filtrar(store.data.agendamentos);
        const container = $in(root, '#lista-agendamentos');
        const vazio = $in(root, '#lista-vazia');

        if (!lista.length) {
            if (container) container.innerHTML = '';
            vazio?.classList.remove('hidden');
            return;
        }

        vazio?.classList.add('hidden');
        container.innerHTML = lista.map((a) => `
            <article class="cc-card-hover p-5 animate-fade-in" data-id="${a.id}">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex gap-4">
                        <div class="hidden sm:flex h-14 w-14 flex-col items-center justify-center rounded-xl bg-wine-light text-wine shrink-0">
                            <span class="text-[10px] font-semibold uppercase">${a.data.split('-')[1]}</span>
                            <span class="text-lg font-bold leading-none">${a.data.split('-')[2]}</span>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-semibold text-ink">${a.servico}</h3>
                                ${badgeHtml(a.status)}
                            </div>
                            <p class="mt-1 text-sm text-ink-muted">${a.profissional} · ${a.hora} · ${formatDate(a.data)}</p>
                            <p class="mt-1 text-sm font-semibold text-wine">${formatCurrency(a.preco)}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" data-detalhes="${a.id}" class="cc-btn-secondary !py-2 transition hover:shadow-sm">Detalhes</button>
                        ${a.status !== 'cancelado' ? `<button type="button" data-cancelar="${a.id}" class="cc-btn-danger transition">Cancelar</button>` : ''}
                    </div>
                </div>
            </article>
        `).join('');

        $$in(container, '[data-detalhes]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const item = store.data.agendamentos.find((x) => String(x.id) === String(btn.dataset.detalhes));
                if (!item) {
                    Toast.show('Agendamento não encontrado', 'error');
                    return;
                }
                renderDetalhesModal(item);
                Modal.open('modal-detalhes');
            });
        });

        $$in(container, '[data-cancelar]').forEach((btn) => {
            btn.addEventListener('click', async () => {
                const id = Number(btn.dataset.cancelar);
                const item = store.data.agendamentos.find((x) => x.id === id);
                if (!item) return;

                if (!confirm(`Cancelar agendamento de ${item.servico}?`)) return;

                await Loading.wrap(delay(500), 'Cancelando agendamento...');
                item.status = 'cancelado';
                item.observacoes = (item.observacoes ? item.observacoes + ' ' : '') + '(Cancelado em ' + new Date().toLocaleDateString('pt-BR') + ')';
                store.save();
                render();
                Toast.show('Agendamento cancelado com sucesso', 'info');
            });
        });
    }

    $$in(root, '[data-filtro]').forEach((btn) => {
        btn.addEventListener('click', () => {
            filtroStatus = btn.dataset.filtro;
            $$in(root, '[data-filtro]').forEach((b) => {
                const active = b === btn;
                b.classList.toggle('bg-wine', active);
                b.classList.toggle('text-white', active);
                b.classList.toggle('shadow-sm', active);
                b.classList.toggle('bg-white', !active);
                b.classList.toggle('text-ink-muted', !active);
            });
            render();
        });
    });

    render();
}
