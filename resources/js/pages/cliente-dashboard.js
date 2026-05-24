import { Toast, Modal, formatCurrency, formatDate } from '../core/ui.js';
import { Store, getHorariosDisponiveis, normalizeDate } from '../core/store.js';
import { getSession } from '../core/session.js';
import { $id, $in } from '../core/dom.js';

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

export function initClienteDashboard(initial) {
    const store = new Store(initial);
    const el = document.getElementById('cliente-dashboard');
    if (!el) return;

    const state = {
        servicoId: '',
        profissionalId: '',
        data: normalizeDate(new Date().toISOString().split('T')[0]),
        horaSelecionada: null,
        enviando: false,
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

    function mapAgendamentoApi(row) {
        const prof = profissionais.find((p) => p.nome === row.profissional);
        const serv = servicos.find((s) => s.nome === row.servico);
        return {
            id: row.id,
            cliente: row.cliente,
            servico: row.servico,
            servico_id: serv?.id ?? row.servico_id,
            profissional: row.profissional,
            profissional_id: prof?.id ?? row.profissional_id ?? state.profissionalId,
            data: normalizeDate(row.data),
            hora: row.horario ?? row.hora,
            status: row.status ?? 'pendente',
            preco: serv?.preco ?? row.preco,
            valor: serv?.preco ?? row.valor,
            observacoes: row.observacoes ?? 'Aguardando confirmação do profissional.',
        };
    }

    function mergeAgendamentos(remotos) {
        const locais = Array.isArray(store.data.agendamentos) ? store.data.agendamentos : [];
        const ids = new Set(remotos.map((a) => String(a.id)));
        const extras = locais.filter((a) => !ids.has(String(a.id)));
        store.data.agendamentos = [...remotos, ...extras];
        store.save();
    }

    async function carregarAgendamentosApi() {
        try {
            const response = await fetch('/appointments', {
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });
            if (!response.ok) return;
            const lista = await response.json();
            if (!Array.isArray(lista)) return;
            mergeAgendamentos(lista.map(mapAgendamentoApi));
        } catch {
            /* mantém localStorage */
        }
    }

    function syncAgendarButton() {
        const btn = $('#btn-agendar');
        if (!btn) return;
        const pronto =
            Boolean(state.profissionalId && state.data && state.horaSelecionada) && !state.enviando;
        btn.dataset.ready = pronto ? '1' : '0';
        btn.classList.toggle('opacity-50', !pronto);
        btn.classList.toggle('cursor-not-allowed', !pronto);
        btn.removeAttribute('disabled');
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
            if (pEmpty) pEmpty.textContent = 'Selecione profissional e data para ver os horários';
            if (summary) summary.textContent = 'Selecione profissional e data';
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
            if (summary) summary.textContent = `${formatDate(state.data)} · Barbearia fechada`;
            syncAgendarButton();
            return;
        }

        const servico = getServico();
        const servicoLabel = servico ? ` · ${servico.nome}` : '';
        if (summary) {
            summary.textContent = `${formatDate(state.data)} · ${prof.nome}${servicoLabel} · Seg–Sex 07:00–18:00`;
        }

        if (!slots.length) {
            grid.innerHTML = '';
            empty?.classList.remove('hidden');
            const p = empty?.querySelector('p');
            if (p) p.textContent = 'Nenhum horário disponível para esta data';
            syncAgendarButton();
            return;
        }

        grid.innerHTML = slots
            .map((slot) => {
                if (!slot.disponivel) {
                    return `<button type="button" disabled class="cc-slot cc-slot-disabled" title="Horário ocupado">${slot.hora}</button>`;
                }
                const selected = state.horaSelecionada === slot.hora;
                return `<button type="button" data-hora="${slot.hora}" class="cc-slot ${selected ? 'cc-slot-selected' : ''}">${slot.hora}</button>`;
            })
            .join('');

        grid.querySelectorAll('[data-hora]').forEach((btn) => {
            btn.addEventListener('click', () => {
                state.horaSelecionada = btn.dataset.hora;
                renderHorarios(buscarHorarios());
                const hint = $('#selection-hint');
                if (hint) {
                    if (!state.servicoId) {
                        hint.textContent = 'Selecione um serviço e clique em Solicitar horário';
                    } else {
                        hint.textContent = `Horário ${state.horaSelecionada} selecionado — clique em Solicitar horário`;
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

    function abrirConfirmacao(servico, prof) {
        const confirmEl = $id('confirmacao-detalhes');
        if (confirmEl) {
            confirmEl.innerHTML = `
                <p><strong>${servico.nome}</strong> com ${prof.nome}</p>
                <p class="text-ink-muted mt-1">${formatDate(state.data)} às ${state.horaSelecionada}</p>
                <p class="mt-2 text-sm text-amber-700">Status: pendente — o profissional irá confirmar.</p>
                <p class="mt-2 text-wine font-semibold">${formatCurrency(servico.preco)}</p>
            `;
        }
        if (document.getElementById('modal-confirmacao')) {
            Modal.open('modal-confirmacao');
        }
    }

    function finalizarSucesso(servico, prof, agendamento) {
        if (!Array.isArray(store.data.agendamentos)) {
            store.data.agendamentos = [];
        }
        store.data.agendamentos.unshift(agendamento);
        store.save();

        window.dispatchEvent(new CustomEvent('corteclick:agendamentos-updated'));

        abrirConfirmacao(servico, prof);

        state.horaSelecionada = null;
        const hint = $('#selection-hint');
        if (hint) hint.textContent = 'Selecione um horário para agendar';

        atualizarHorarios();
        Toast.show('Agendamento solicitado com sucesso', 'success');
    }

    async function solicitarAgendamento() {
        if (state.enviando) return;

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
        const hora = state.horaSelecionada;
        const data = normalizeDate(state.data);

        const payload = {
            cliente: session?.nome || 'Cliente',
            profissional: prof.nome,
            servico: servico.nome,
            data,
            horario: hora,
        };

        const agendamentoLocal = {
            id: Date.now(),
            cliente: payload.cliente,
            servico: servico.nome,
            servico_id: servico.id,
            profissional: prof.nome,
            profissional_id: prof.id,
            data,
            hora,
            status: 'pendente',
            preco: servico.preco,
            valor: servico.preco,
            observacoes: 'Aguardando confirmação do profissional.',
        };

        state.enviando = true;
        syncAgendarButton();

        try {
            const response = await fetch('/appointments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify(payload),
            });

            let body = {};
            const contentType = response.headers.get('content-type') ?? '';
            if (contentType.includes('application/json')) {
                body = await response.json();
            }

            if (!response.ok) {
                throw new Error(body.message || `Erro ao salvar (${response.status})`);
            }

            const salvo = body.data ?? body;
            agendamentoLocal.id = salvo.id ?? agendamentoLocal.id;

            finalizarSucesso(servico, prof, agendamentoLocal);
        } catch (error) {
            console.error(error);
            Toast.show(error.message || 'Erro ao salvar agendamento', 'error');
        } finally {
            state.enviando = false;
            syncAgendarButton();
        }
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

    const btnAgendar = $('#btn-agendar');
    if (btnAgendar) {
        btnAgendar.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (btnAgendar.dataset.ready !== '1') {
                if (!state.horaSelecionada) {
                    Toast.show('Selecione um horário disponível', 'warning');
                } else if (!state.servicoId) {
                    Toast.show('Selecione um serviço', 'warning');
                } else if (!state.profissionalId || !state.data) {
                    Toast.show('Selecione profissional e data', 'warning');
                }
                return;
            }
            solicitarAgendamento();
        });
    }

    const inputData = $('#filtro-data');
    if (inputData) {
        inputData.min = normalizeDate(new Date().toISOString().split('T')[0]);
        if (inputData.value) state.data = normalizeDate(inputData.value);
    }

    carregarAgendamentosApi().finally(() => {
        renderHorarios({ weekend: false, slots: [] });
        syncAgendarButton();
    });
}
