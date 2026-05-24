
import { Toast, Modal, formatCurrency, formatDate } from '../core/ui.js';
import { Store, getHorariosDisponiveis, normalizeDate } from '../core/store.js';
import { getSession } from '../core/session.js';
import { $id, $in } from '../core/dom.js';

export function initClienteDashboard(initial) {
    const store = new Store(initial);
    const el = document.getElementById('cliente-dashboard');

    if (!el) return;

    const state = {
        servicoId: '',
        profissionalId: '',
        data: normalizeDate(new Date().toISOString().split('T')[0]),
        horaSelecionada: null,
    };

    const $ = (sel) => $in(el, sel);

    const servicos = store.data.servicos ?? [];
    const profissionais = store.data.profissionais ?? [];

    function getServico() {
        return servicos.find((s) => String(s.id) === String(state.servicoId));
    }

    function getProfissional() {
        return profissionais.find((p) => String(p.id) === String(state.profissionalId));
    }

    function syncAgendarButton() {
        const btn = $('#btn-agendar');

        if (!btn) return;

        const pronto = Boolean(
            state.profissionalId &&
            state.data &&
            state.horaSelecionada
        );

        btn.disabled = !pronto;
    }

    function buscarHorarios() {
        return getHorariosDisponiveis(
            state.profissionalId,
            state.data,
            store.data.agendamentos ?? [],
        );
    }

    function renderHorarios(result) {
        const grid = $('#horarios-grid');
        const empty = $('#horarios-empty');
        const weekend = $('#horarios-weekend');
        const summary = $('#horarios-summary');

        const slots = result?.slots ?? [];
        const isWeekendDay = result?.weekend === true;

        weekend?.classList.add('hidden');
        empty?.classList.add('hidden');

        if (!state.profissionalId || !state.data) {
            grid.innerHTML = '';

            empty?.classList.remove('hidden');

            const pEmpty = empty?.querySelector('p');

            if (pEmpty) {
                pEmpty.textContent = 'Selecione profissional e data para ver os horários';
            }

            if (summary) {
                summary.textContent = 'Selecione profissional e data';
            }

            syncAgendarButton();

            return;
        }

        const prof = getProfissional();

        if (!prof) {
            grid.innerHTML = '';

            empty?.classList.remove('hidden');

            syncAgendarButton();

            return;
        }

        if (isWeekendDay) {
            grid.innerHTML = '';

            weekend?.classList.remove('hidden');

            if (summary) {
                summary.textContent = `${formatDate(state.data)} · Barbearia fechada`;
            }

            syncAgendarButton();

            return;
        }

        const servico = getServico();

        const servicoLabel = servico ? ` · ${servico.nome}` : '';

        if (summary) {
            summary.textContent =
                `${formatDate(state.data)} · ${prof.nome}${servicoLabel} · Seg–Sex 07:00–18:00`;
        }

        if (!slots.length) {
            grid.innerHTML = '';

            empty?.classList.remove('hidden');

            const p = empty?.querySelector('p');

            if (p) {
                p.textContent = 'Nenhum horário disponível para esta data';
            }

            syncAgendarButton();

            return;
        }

        grid.innerHTML = slots
            .map((slot) => {
                if (!slot.disponivel) {
                    return `
                        <button
                            type="button"
                            disabled
                            class="cc-slot cc-slot-disabled"
                            title="Horário ocupado"
                        >
                            ${slot.hora}
                        </button>
                    `;
                }

                const selected = state.horaSelecionada === slot.hora;

                return `
                    <button
                        type="button"
                        data-hora="${slot.hora}"
                        class="cc-slot ${selected ? 'cc-slot-selected' : ''}"
                    >
                        ${slot.hora}
                    </button>
                `;
            })
            .join('');

        grid.querySelectorAll('[data-hora]').forEach((btn) => {
            btn.addEventListener('click', () => {
                state.horaSelecionada = btn.dataset.hora;

                renderHorarios(buscarHorarios());

                const hint = $('#selection-hint');

                if (hint) {
                    if (!state.servicoId) {
                        hint.textContent =
                            'Selecione um serviço e clique em Solicitar horário';
                    } else {
                        hint.textContent =
                            `Horário ${state.horaSelecionada} selecionado — clique em Solicitar horário`;
                    }
                }

                syncAgendarButton();
            });
        });

        syncAgendarButton();
    }

    function atualizarHorarios() {
        if (!state.profissionalId || !state.data) {
            renderHorarios({ weekend: false, slots: [] });

            return;
        }

        renderHorarios(buscarHorarios());
    }

    function solicitarAgendamento() {
        if (!state.profissionalId || !state.data) {
            Toast.show('Selecione profissional e data', 'warning');

            return;
        }

        if (!state.servicoId) {
            Toast.show('Selecione um serviço', 'warning');

            return;
        }

        if (!state.horaSelecionada) {
            Toast.show('Selecione um horário disponível', 'warning');

            return;
        }

        const servico = getServico();
        const prof = getProfissional();

        if (!servico || !prof) return;

        const session = getSession();

        const novo = {
            id: Date.now(),
            cliente: session?.nome || 'Cliente',
            servico: servico.nome,
            servico_id: servico.id,
            profissional: prof.nome,
            profissional_id: prof.id,
            data: normalizeDate(state.data),
            hora: state.horaSelecionada,
            status: 'pendente',
            preco: servico.preco,
            valor: servico.preco,
            observacoes: 'Aguardando confirmação do profissional.',
        };

        fetch('http://127.0.0.1:8000/appointments', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                cliente: novo.cliente,
                profissional: novo.profissional,
                servico: novo.servico,
                data: novo.data,
                horario: novo.hora,
            }),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Agendamento salvo:', data);

            window.dispatchEvent(
                new CustomEvent('corteclick:agendamentos-updated')
            );

            const confirmEl = $id('confirmacao-detalhes');

            if (confirmEl) {
                confirmEl.innerHTML = `
                    <p><strong>${servico.nome}</strong> com ${prof.nome}</p>
                    <p class="text-ink-muted mt-1">
                        ${formatDate(state.data)} às ${state.horaSelecionada}
                    </p>
                    <p class="mt-2 text-sm text-amber-700">
                        Status: pendente — o profissional irá confirmar.
                    </p>
                    <p class="mt-2 text-wine font-semibold">
                        ${formatCurrency(servico.preco)}
                    </p>
                `;
            }

            if (document.getElementById('modal-confirmacao')) {
                Modal.open('modal-confirmacao');
            }

            state.horaSelecionada = null;

            const hint = $('#selection-hint');

            if (hint) {
                hint.textContent = 'Selecione um horário para agendar';
            }

            syncAgendarButton();

            atualizarHorarios();

            Toast.show(
                'Agendamento solicitado com sucesso',
                'success'
            );
        })
        .catch(error => {
            console.error(error);

            Toast.show(
                'Erro ao salvar agendamento',
                'error'
            );
        });
    }

    $('#filtro-servico')?.addEventListener('change', (e) => {
        state.servicoId = e.target.value;
        state.horaSelecionada = null;

        atualizarHorarios();
    });

    $('#filtro-profissional')?.addEventListener('change', (e) => {
        state.profissionalId = e.target.value;
        state.horaSelecionada = null;

        atualizarHorarios();
    });

    $('#filtro-data')?.addEventListener('change', (e) => {
        state.data = normalizeDate(e.target.value);
        state.horaSelecionada = null;

        atualizarHorarios();
    });

    $('#btn-buscar')?.addEventListener('click', () => {
        if (!state.profissionalId || !state.data) {
            Toast.show('Selecione profissional e data', 'warning');

            return;
        }

        atualizarHorarios();
    });

    el.addEventListener('click', (e) => {
        if (e.target.closest('#btn-agendar')) {
            e.preventDefault();

            solicitarAgendamento();
        }
    });

    const inputData = $('#filtro-data');

    if (inputData) {
        inputData.min =
            normalizeDate(new Date().toISOString().split('T')[0]);

        if (inputData.value) {
            state.data = normalizeDate(inputData.value);
        }
    }

    renderHorarios({ weekend: false, slots: [] });

    syncAgendarButton();
}

