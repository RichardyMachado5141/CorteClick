import { formatCurrency } from './ui.js';

export const STORAGE_KEY = 'corteclick_store_v2';

export class Store {
    constructor(initial = {}) {
        this.data = this.load(initial);
    }

    load(initial) {
        try {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved) {
                const parsed = JSON.parse(saved);
                return {
                    ...structuredClone(initial),
                    ...parsed,
                    agendamentos: parsed.agendamentos ?? initial.agendamentos ?? [],
                    servicos: parsed.servicos ?? initial.servicos ?? [],
                    usuarios: parsed.usuarios ?? initial.usuarios ?? [],
                };
            }
        } catch (_) {}
        return structuredClone(initial);
    }

    save() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(this.data));
        } catch (_) {}
    }
}

/** Normaliza data para YYYY-MM-DD */
export function normalizeDate(dateStr) {
    if (!dateStr) return '';
    const [y, m, d] = String(dateStr).split('-');
    if (!y || !m || !d) return dateStr;
    return `${y}-${m.padStart(2, '0')}-${d.padStart(2, '0')}`;
}

/** Normaliza hora para HH:MM */
export function normalizeHora(hora) {
    if (!hora) return '';
    const parts = String(hora).split(':');
    return `${parts[0].padStart(2, '0')}:${(parts[1] || '00').padStart(2, '0')}`;
}

export function parseLocalDate(dateStr) {
    return new Date(`${normalizeDate(dateStr)}T12:00:00`);
}

/** Segunda a sexta */
export function isWeekday(dateStr) {
    const d = parseLocalDate(dateStr);
    if (Number.isNaN(d.getTime())) return false;
    const day = d.getDay();
    return day >= 1 && day <= 5;
}

export function isWeekend(dateStr) {
    const d = parseLocalDate(dateStr);
    if (Number.isNaN(d.getTime())) return false;
    const day = d.getDay();
    return day === 0 || day === 6;
}

/** Horários comerciais: 07:00–18:00, intervalo 30 min, sem almoço 12:00–14:00 */
export function getBusinessTimeSlots() {
    const slots = [];
    for (let hour = 7; hour <= 18; hour++) {
        for (const minute of [0, 30]) {
            if (hour === 18 && minute === 30) continue;
            if (hour >= 12 && hour < 14) continue;
            slots.push(`${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`);
        }
    }
    return slots;
}

export function isSlotInLunch(hora) {
    const [h] = normalizeHora(hora).split(':').map(Number);
    return h >= 12 && h < 14;
}

/**
 * Gera horários para uma data e profissional.
 * @returns {{ weekend: boolean, slots: Array<{hora: string, disponivel: boolean}> }}
 */
export function getHorariosDisponiveis(profissionalId, data, agendamentos = []) {
    const dataNorm = normalizeDate(data);

    if (isWeekend(dataNorm)) {
        return { weekend: true, slots: [] };
    }

    if (!isWeekday(dataNorm)) {
        return { weekend: true, slots: [] };
    }

    const lista = Array.isArray(agendamentos) ? agendamentos : [];

    const ocupados = lista
        .filter(
            (a) =>
                normalizeDate(a.data) === dataNorm &&
                String(a.profissional_id) === String(profissionalId) &&
                a.status !== 'cancelado',
        )
        .map((a) => normalizeHora(a.hora));

    const slots = getBusinessTimeSlots().map((hora) => ({
        hora,
        disponivel: !ocupados.includes(hora),
    }));

    return { weekend: false, slots };
}

/** @deprecated use getHorariosDisponiveis */
export function generateHorarios(profissionalId, data) {
    return getHorariosDisponiveis(profissionalId, data, []);
}

export function badgeHtml(status) {
    const map = {
        confirmado: 'cc-badge-confirmado',
        pendente: 'cc-badge-pendente',
        cancelado: 'cc-badge-cancelado',
    };
    const cls = map[status] || 'cc-badge bg-gray-100 text-gray-600';
    const label = status.charAt(0).toUpperCase() + status.slice(1);
    return `<span class="${cls}">${label}</span>`;
}

export function getAgendamentosProfissional(agendamentos, profissionalId, data) {
    const dataNorm = normalizeDate(data);
    return (agendamentos ?? []).filter(
        (a) =>
            normalizeDate(a.data) === dataNorm &&
            String(a.profissional_id) === String(profissionalId),
    );
}

export { formatCurrency };
