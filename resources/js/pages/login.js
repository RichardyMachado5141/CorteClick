import { Loading, delay } from '../core/ui.js';
import { parseLoginInput, saveSession, getDashboardPath } from '../core/session.js';

export function initLogin() {
    const form = document.getElementById('login-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const input = form.querySelector('[name="login"]');
        const password = form.querySelector('[name="password"]');
        const loginValue = input?.value?.trim() ?? '';

        if (!loginValue) {
            window.Toast?.show('Informe seu e-mail', 'warning');
            return;
        }
        if (!password?.value?.trim()) {
            window.Toast?.show('Informe sua senha', 'warning');
            return;
        }

        const user = parseLoginInput(loginValue);
        saveSession({
            ...user,
            authenticated: true,
            loggedAt: new Date().toISOString(),
        });

        await Loading.wrap(delay(900), `Entrando, ${user.nome}...`);

        window.location.href = getDashboardPath(user.role);
    });
}
