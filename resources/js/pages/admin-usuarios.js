import { Toast, delay, Loading } from '../core/ui.js';
import { Store } from '../core/store.js';
import { $id, $in, $$in } from '../core/dom.js';

function toggleButtonHtml(id, ativo) {
    return `
        <button
            type="button"
            role="switch"
            data-toggle="${id}"
            aria-checked="${ativo}"
            class="toggle-usuario relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-wine/30 focus:ring-offset-2 ${ativo ? 'bg-wine' : 'bg-gray-200'}"
        >
            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200 ease-in-out ${ativo ? 'translate-x-5' : 'translate-x-0'}"></span>
        </button>
    `;
}

export function initAdminUsuarios(initial) {
    const store = new Store(initial);
    const root = document.getElementById('admin-usuarios');
    if (!root) return;

    function filtrar() {
        const busca = ($in(root, '#busca')?.value || '').toLowerCase();
        const perfil = $in(root, '#filtro-perfil')?.value || '';
        const status = $in(root, '#filtro-status')?.value || '';

        return store.data.usuarios.filter((u) => {
            const matchBusca = !busca || [u.nome, u.email, u.telefone].some((f) => f.toLowerCase().includes(busca));
            const matchPerfil = !perfil || u.perfil === perfil;
            const matchStatus = !status || (status === 'ativo' ? u.ativo : !u.ativo);
            return matchBusca && matchPerfil && matchStatus;
        });
    }

    function updateRowVisual(row, ativo) {
        const badge = row.querySelector('[data-status-badge]');
        const toggle = row.querySelector('[data-toggle]');
        if (badge) {
            badge.className = `cc-badge ${ativo ? 'cc-badge-confirmado' : 'cc-badge-cancelado'}`;
            badge.textContent = ativo ? 'Ativo' : 'Inativo';
        }
        if (toggle) {
            toggle.setAttribute('aria-checked', String(ativo));
            toggle.classList.toggle('bg-wine', ativo);
            toggle.classList.toggle('bg-gray-200', !ativo);
            const knob = toggle.querySelector('span');
            if (knob) {
                knob.classList.toggle('translate-x-5', ativo);
                knob.classList.toggle('translate-x-0', !ativo);
            }
        }
    }

    function render() {
        const lista = filtrar();
        const tbody = $in(root, '#usuarios-tbody');
        if (!tbody) return;

        tbody.innerHTML = lista.map((u) => `
            <tr class="border-b border-border transition hover:bg-gray-50/80" data-id="${u.id}">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-wine-light text-xs font-bold text-wine">${u.nome.substring(0, 2).toUpperCase()}</div>
                        <span class="font-medium text-ink">${u.nome}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-ink-muted">
                    <div>${u.email}</div>
                    <div class="text-xs">${u.telefone}</div>
                </td>
                <td class="px-6 py-4"><span class="cc-badge bg-gray-100 text-ink-muted">${u.perfil}</span></td>
                <td class="px-6 py-4 text-sm text-ink-muted">${u.cadastro}</td>
                <td class="px-6 py-4">
                    <span data-status-badge class="cc-badge ${u.ativo ? 'cc-badge-confirmado' : 'cc-badge-cancelado'}">${u.ativo ? 'Ativo' : 'Inativo'}</span>
                </td>
                <td class="px-6 py-4">${toggleButtonHtml(u.id, u.ativo)}</td>
            </tr>
        `).join('');

        $$in(tbody, '[data-toggle]').forEach((btn) => {
            btn.addEventListener('click', async () => {
                const id = Number(btn.dataset.toggle);
                const u = store.data.usuarios.find((x) => x.id === id);
                if (!u) return;

                const novoEstado = !u.ativo;
                btn.disabled = true;

                await Loading.wrap(delay(350), novoEstado ? 'Ativando usuário...' : 'Desativando usuário...');

                u.ativo = novoEstado;
                store.save();

                const row = btn.closest('tr');
                if (row) updateRowVisual(row, novoEstado);

                btn.disabled = false;

                Toast.show(
                    novoEstado ? 'Usuário ativado com sucesso' : 'Usuário desativado com sucesso',
                    'success',
                );
            });
        });

        const contador = $in(root, '#contador');
        if (contador) contador.textContent = `${lista.length} usuário(s) encontrado(s)`;
    }

    $in(root, '#busca')?.addEventListener('input', render);
    $in(root, '#filtro-perfil')?.addEventListener('change', render);
    $in(root, '#filtro-status')?.addEventListener('change', render);

    render();
}
