import { Toast, formatCurrency, formatDate } from '../core/ui.js';
import { Store, badgeHtml, normalizeDate } from '../core/store.js';
import { $in, $$in } from '../core/dom.js';

export function initAdminAgendamentos(initial) {
    const store = new Store(initial);
    const root = document.getElementById('admin-agendamentos');
    if (!root) return;

    let filtroStatus = 'todos';

    function getAgendamentos() {
        return Array.isArray(store.data.agendamentos) ? store.data.agendamentos : [];
    }

    function filtrar() {
        const data = normalizeDate($in(root, '#filtro-data')?.value || '');
        const status = filtroStatus === 'todos' ? '' : filtroStatus;

        return getAgendamentos().filter((a) => {
            const matchData = !data || normalizeDate(a.data) === data;
            const matchStatus = !status || a.status === status;
            return matchData && matchStatus;
        });
    }

    function setFiltroAtivo(status) {
        filtroStatus = status;

        $$in(root, '[data-filtro]').forEach((btn) => {
            const active = btn.dataset.filtro === status;
            btn.classList.toggle('bg-wine', active);
            btn.classList.toggle('text-white', active);
            btn.classList.toggle('shadow-sm', active);
            btn.classList.toggle('bg-white', !active);
            btn.classList.toggle('text-ink-muted', !active);
        });

        const select = $in(root, '#filtro-status');
        if (select) {
            select.value = status === 'todos' ? '' : status;
        }
    }

    function render() {
        const lista = filtrar();
        const container = $in(root, '#lista-agendamentos');
        const vazia = $in(root, '#lista-vazia');
        if (!container) return;

        container.style.opacity = '0';
        container.style.transition = 'opacity 0.2s ease';

        requestAnimationFrame(() => {
            if (!lista.length) {
                container.innerHTML = '';
                vazia?.classList.remove('hidden');
            } else {
                vazia?.classList.add('hidden');
                container.innerHTML = lista
                    .map(
                        (a) => `
            <article class="cc-card-hover p-5 animate-fade-in">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="grid flex-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <div><p class="text-xs text-ink-light">Cliente</p><p class="font-medium">${a.cliente}</p></div>
                        <div><p class="text-xs text-ink-light">Profissional</p><p class="font-medium">${a.profissional}</p></div>
                        <div><p class="text-xs text-ink-light">Serviço</p><p class="font-medium">${a.servico}</p></div>
                        <div><p class="text-xs text-ink-light">Data / Hora</p><p class="font-medium">${formatDate(a.data)} às ${a.hora}</p></div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-lg font-bold text-wine">${formatCurrency(a.valor ?? a.preco ?? 0)}</p>
                            ${badgeHtml(a.status)}
                        </div>
                        <select data-agendamento-id="${a.id}" class="cc-input !w-auto !py-2 text-sm">
                            <option value="confirmado" ${a.status === 'confirmado' ? 'selected' : ''}>Confirmado</option>
                            <option value="pendente" ${a.status === 'pendente' ? 'selected' : ''}>Pendente</option>
                            <option value="cancelado" ${a.status === 'cancelado' ? 'selected' : ''}>Cancelado</option>
                        </select>
                    </div>
                </div>
            </article>
        `,
                    )
                    .join('');
            }

            $$in(container, '[data-agendamento-id]').forEach((sel) => {
                sel.addEventListener('change', () => {
                    const id = sel.dataset.agendamentoId;
                    const item = getAgendamentos().find((x) => String(x.id) === String(id));
                    if (!item) return;
                    item.status = sel.value;
                    store.save();
                    render();
                    Toast.show('Status atualizado com sucesso', 'success');
                });
            });

            const contador = $in(root, '#contador');
            if (contador) {
                const labelStatus =
                    filtroStatus === 'todos'
                        ? 'todos os status'
                        : filtroStatus;
                contador.textContent = `${lista.length} agendamento(s) · ${labelStatus}`;
            }

            container.style.opacity = '1';
        });
    }

    $$in(root, '[data-filtro]').forEach((btn) => {
        btn.addEventListener('click', () => {
            setFiltroAtivo(btn.dataset.filtro);
            render();
        });
    });

    $in(root, '#filtro-status')?.addEventListener('change', (e) => {
        const value = e.target.value || 'todos';
        setFiltroAtivo(value);
        render();
    });

    $in(root, '#filtro-data')?.addEventListener('change', render);

    $in(root, '#btn-filtrar')?.addEventListener('click', render);

    setFiltroAtivo('todos');
    render();
}
