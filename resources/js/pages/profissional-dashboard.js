import { Toast } from '../core/ui.js';
import {
    Store,
    badgeHtml,
    getBusinessTimeSlots,
    getAgendamentosProfissional,
    normalizeDate,
    normalizeHora,
    isWeekend,
} from '../core/store.js';
import { $in, $$in } from '../core/dom.js';

export function initProfissionalDashboard(initial) {
    const root = document.getElementById('profissional-dashboard');
    if (!root) return;

    const profissionalId = initial.profissionalId ?? 1;
    const store = new Store({ agendamentos: initial.agendamentos ?? [] });
    let currentDate = normalizeDate(new Date().toISOString().split('T')[0]);

    const $ = (sel) => $in(root, sel);

    function agendamentosDoDia() {
        return getAgendamentosProfissional(store.data.agendamentos ?? [], profissionalId, currentDate);
    }

    function findAgendamentoPorHora(agendamentos, hora) {
        const horaNorm = normalizeHora(hora);
        return agendamentos.find((a) => normalizeHora(a.hora) === horaNorm);
    }

    function atualizarStats() {
        const doDia = agendamentosDoDia().filter((a) => a.status !== 'cancelado');
        const confirmados = doDia.filter((a) => a.status === 'confirmado').length;
        const pendentes = doDia.filter((a) => a.status === 'pendente').length;
        const slots = getBusinessTimeSlots();
        const ocupados = doDia.length;

        const set = (id, val) => {
            const el = $(`#stat-${id}`);
            if (el) el.textContent = val;
        };
        set('hoje', ocupados);
        set('confirmados', confirmados);
        set('pendentes', pendentes);
        set('livres', Math.max(0, slots.length - ocupados));
    }

    function alterarStatus(id, status) {
        const item = store.data.agendamentos.find((a) => String(a.id) === String(id));
        if (!item) return;
        item.status = status;
        store.save();
        renderSlots();
        Toast.show(
            status === 'confirmado' ? 'Agendamento confirmado com sucesso!' : 'Agendamento recusado',
            status === 'confirmado' ? 'success' : 'info',
        );
    }

    function renderSlots() {
        const grid = $('#agenda-grid');
        const empty = $('#agenda-empty');

        atualizarStats();

        if (isWeekend(currentDate)) {
            grid.innerHTML = '';
            empty?.classList.remove('hidden');
            const title = empty?.querySelector('p.font-medium');
            const sub = empty?.querySelector('p.text-sm');
            if (title) title.textContent = 'Barbearia fechada aos finais de semana';
            if (sub) sub.textContent = 'Atendemos de segunda a sexta, das 07:00 às 18:00';
            return;
        }

        empty?.classList.add('hidden');

        const agendamentos = agendamentosDoDia();
        const slots = getBusinessTimeSlots();
        const html = [];
        let lunchInserted = false;

        slots.forEach((hora) => {
            if (!lunchInserted && hora >= '14:00') {
                lunchInserted = true;
                html.push(`
                    <div class="col-span-full flex items-center gap-3 py-2">
                        <div class="h-px flex-1 bg-border"></div>
                        <span class="text-xs font-medium text-ink-muted">Almoço · 12:00 – 14:00</span>
                        <div class="h-px flex-1 bg-border"></div>
                    </div>
                `);
            }

            const ag = findAgendamentoPorHora(agendamentos, hora);

            if (!ag || ag.status === 'cancelado') {
                if (ag?.status === 'cancelado') {
                    html.push(`
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 opacity-75">
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-ink-muted">${hora}</span>
                                ${badgeHtml('cancelado')}
                            </div>
                            <p class="mt-2 text-sm text-ink-muted line-through">${ag.cliente || ag.servico}</p>
                        </div>
                    `);
                } else {
                    html.push(`
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 transition hover:shadow-md">
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-ink">${hora}</span>
                                <span class="cc-badge bg-emerald-100 text-emerald-700">Livre</span>
                            </div>
                            <p class="mt-2 text-sm text-emerald-700/80">Disponível</p>
                        </div>
                    `);
                }
                return;
            }

            const cliente = ag.cliente || 'Cliente';
            const isPendente = ag.status === 'pendente';
            const isConfirmado = ag.status === 'confirmado';

            html.push(`
                <div class="rounded-2xl border ${isPendente ? 'border-amber-200 bg-amber-50/50' : isConfirmado ? 'border-wine/20 bg-wine-light/40' : 'border-gray-200 bg-gray-50'} p-4 transition hover:shadow-md">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-lg font-bold text-ink">${hora}</span>
                        ${badgeHtml(ag.status)}
                    </div>
                    <p class="mt-2 font-medium text-ink">${cliente}</p>
                    <p class="text-sm text-ink-muted">${ag.servico || 'Serviço'}</p>
                    ${isPendente ? `
                        <div class="mt-4 flex gap-2 border-t border-amber-200/60 pt-3">
                            <button type="button" data-confirmar="${ag.id}" class="cc-btn-primary flex-1 !py-2 text-xs">Confirmar</button>
                            <button type="button" data-recusar="${ag.id}" class="cc-btn-danger flex-1 !py-2 text-xs">Recusar</button>
                        </div>
                    ` : ''}
                </div>
            `);
        });

        grid.innerHTML = html.join('');

        $$in(grid, '[data-confirmar]').forEach((btn) => {
            btn.addEventListener('click', () => alterarStatus(btn.dataset.confirmar, 'confirmado'));
        });

        $$in(grid, '[data-recusar]').forEach((btn) => {
            btn.addEventListener('click', () => {
                if (!confirm('Recusar este agendamento?')) return;
                alterarStatus(btn.dataset.recusar, 'cancelado');
            });
        });
    }

    function updateDateLabel() {
        const d = new Date(`${currentDate}T12:00:00`);
        const label = $('#data-label');
        if (label) {
            label.textContent = d.toLocaleDateString('pt-BR', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
            });
        }
        const input = $('#filtro-data-agenda');
        if (input) input.value = currentDate;
    }

    function mudarData(delta) {
        const d = new Date(`${currentDate}T12:00:00`);
        d.setDate(d.getDate() + delta);
        currentDate = normalizeDate(d.toISOString().split('T')[0]);
        updateDateLabel();
        renderSlots();
    }

    $('#btn-prev')?.addEventListener('click', () => mudarData(-1));
    $('#btn-next')?.addEventListener('click', () => mudarData(1));

    $('#filtro-data-agenda')?.addEventListener('change', (e) => {
        currentDate = normalizeDate(e.target.value);
        updateDateLabel();
        renderSlots();
    });

    function recarregarAgendamentos() {
        store.data = store.load({ agendamentos: initial.agendamentos ?? [] });
        renderSlots();
    }

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            recarregarAgendamentos();
        }
    });

    window.addEventListener('corteclick:agendamentos-updated', recarregarAgendamentos);
    window.addEventListener('storage', (e) => {
        if (e.key === 'corteclick_store_v2') {
            recarregarAgendamentos();
        }
    });

    const inputAgenda = $('#filtro-data-agenda');
    if (inputAgenda?.value) currentDate = normalizeDate(inputAgenda.value);

    updateDateLabel();
    renderSlots();
}
