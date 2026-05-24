import { Toast, Modal, Loading } from './core/ui.js';
import {
    applySessionToUI,
    saveSession,
    clearSession,
    enforceSessionAccess,
} from './core/session.js';
import { initLogin } from './pages/login.js';
import { initClienteDashboard } from './pages/cliente-dashboard.js';
import { initClienteAgendamentos } from './pages/cliente-agendamentos.js';
import { initProfissionalDashboard } from './pages/profissional-dashboard.js';
import { initProfissionalServicos } from './pages/profissional-servicos.js';
import { initAdminUsuarios } from './pages/admin-usuarios.js';
import { initAdminAgendamentos } from './pages/admin-agendamentos.js';

Toast.init();
Loading.init();
Modal.init();

document.addEventListener('DOMContentLoaded', () => {
    enforceSessionAccess();

    const page = document.body.dataset.page;

    applySessionToUI();

    if (page === 'login') {
        initLogin();
    }

    if (page === 'perfil') {
        document.querySelectorAll('[data-perfil]').forEach((link) => {
            link.addEventListener('click', () => {
                saveSession({ role: 'admin' });
            });
        });
    }

    document.querySelectorAll('[data-logout]').forEach((link) => {
        link.addEventListener('click', () => clearSession());
    });

    const dataEl = document.getElementById('page-data');
    if (page && dataEl) {
        let data = {};
        try {
            data = JSON.parse(dataEl.textContent);
        } catch (_) {}

        const pages = {
            'cliente-dashboard': initClienteDashboard,
            'cliente-agendamentos': initClienteAgendamentos,
            'profissional-dashboard': initProfissionalDashboard,
            'profissional-servicos': initProfissionalServicos,
            'admin-usuarios': initAdminUsuarios,
            'admin-agendamentos': initAdminAgendamentos,
        };

        pages[page]?.(data);
    }

    document.querySelectorAll('[data-modal-open]').forEach((btn) => {
        btn.addEventListener('click', () => Modal.open(btn.dataset.modalOpen));
    });

    document.querySelectorAll('form[data-toast]:not(#login-form)').forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            Toast.show(form.dataset.toast || 'Ação realizada com sucesso!', 'success');
            const action = form.getAttribute('action');
            if (action) setTimeout(() => { window.location.href = action; }, 800);
        });
    });
});
