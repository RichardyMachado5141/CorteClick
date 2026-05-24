const SESSION_KEY = 'corteclick_session';

const DASHBOARD_PATHS = {
    admin: '/admin',
    profissional: '/profissional',
    cliente: '/cliente',
};

export function detectRoleFromEmail(email) {
    const value = (email || '').trim().toLowerCase();

    if (!value.includes('@')) {
        return 'cliente';
    }

    if (value.endsWith('@adm.com')) {
        return 'admin';
    }

    if (value.endsWith('@prof.com')) {
        return 'profissional';
    }

    return 'cliente';
}

export function getDashboardPath(role) {
    return DASHBOARD_PATHS[role] || DASHBOARD_PATHS.cliente;
}

export function parseLoginInput(input) {
    const value = (input || '').trim();
    if (!value) return { nome: 'Visitante', email: '', login: '', role: 'cliente' };

    if (value.includes('@')) {
        const email = value.toLowerCase();
        const local = email.split('@')[0];
        const nome = local
            .replace(/[._-]+/g, ' ')
            .split(' ')
            .filter(Boolean)
            .map((part) => part.charAt(0).toUpperCase() + part.slice(1).toLowerCase())
            .join(' ');

        return {
            nome: nome || 'Usuário',
            email,
            login: value,
            role: detectRoleFromEmail(email),
        };
    }

    const digits = value.replace(/\D/g, '');
    return {
        nome: digits ? `Cliente ${digits.slice(-4)}` : 'Cliente',
        email: '',
        login: value,
        telefone: value,
        role: 'cliente',
    };
}

export function getSession() {
    try {
        const raw = localStorage.getItem(SESSION_KEY);
        return raw ? JSON.parse(raw) : null;
    } catch {
        return null;
    }
}

export function saveSession(data) {
    localStorage.setItem(SESSION_KEY, JSON.stringify({ ...getSession(), ...data }));
}

export function clearSession() {
    localStorage.removeItem(SESSION_KEY);
}

export function applySessionToUI() {
    const session = getSession();
    if (!session?.nome) return;

    document.querySelectorAll('[data-user-name]').forEach((el) => {
        el.textContent = session.nome;
    });

    document.querySelectorAll('[data-user-initials]').forEach((el) => {
        const parts = session.nome.trim().split(/\s+/);
        const initials = parts.length >= 2
            ? (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
            : session.nome.substring(0, 2).toUpperCase();
        el.textContent = initials;
    });

    document.querySelectorAll('[data-welcome]').forEach((el) => {
        el.textContent = `Bem-vindo, ${session.nome}`;
    });

    const trocarPerfil = document.querySelector('[data-trocar-perfil]');
    if (trocarPerfil) {
        trocarPerfil.classList.toggle('hidden', session.role !== 'admin');
    }
}

export function isAuthenticated() {
    const session = getSession();
    return Boolean(session?.authenticated && session?.role);
}

export function enforceSessionAccess() {
    const path = window.location.pathname;
    const page = document.body.dataset.page;

    if (page === 'login') {
        clearSession();
        return;
    }

    const session = getSession();

    if (page === 'perfil') {
        if (!isAuthenticated()) {
            window.location.replace('/login');
            return;
        }
        if (session.role !== 'admin') {
            window.location.replace(getDashboardPath(session.role));
        }
        return;
    }

    const isAppArea = path.startsWith('/cliente') || path.startsWith('/profissional') || path.startsWith('/admin');

    if (isAppArea && !isAuthenticated()) {
        window.location.replace('/login');
        return;
    }

    if (!session?.role || session.role === 'admin') {
        return;
    }

    if (session.role === 'cliente' && (path.startsWith('/profissional') || path.startsWith('/admin'))) {
        window.location.replace(getDashboardPath('cliente'));
        return;
    }

    if (session.role === 'profissional' && (path.startsWith('/cliente') || path.startsWith('/admin'))) {
        window.location.replace(getDashboardPath('profissional'));
    }
}
