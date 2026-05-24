const TOAST_DURATION = 4200;
const TOAST_CONTAINER_CLASS = 'fixed right-4 top-4 z-[200] flex flex-col items-end gap-3 pointer-events-none w-full max-w-sm px-4 sm:px-0 sm:w-auto';

const TOAST_STYLES = {
    success: { box: 'border-emerald-200 bg-white text-ink', icon: 'bg-emerald-100 text-emerald-600' },
    error: { box: 'border-red-200 bg-white text-ink', icon: 'bg-red-100 text-red-600' },
    info: { box: 'border-blue-200 bg-white text-ink', icon: 'bg-blue-100 text-blue-600' },
    warning: { box: 'border-amber-200 bg-white text-ink', icon: 'bg-amber-100 text-amber-600' },
};

function createToastIcon(type) {
    const wrap = document.createElement('span');
    wrap.className = `flex h-9 w-9 shrink-0 items-center justify-center rounded-full ${TOAST_STYLES[type]?.icon || TOAST_STYLES.info.icon}`;

    const ns = 'http://www.w3.org/2000/svg';
    const svg = document.createElementNS(ns, 'svg');
    svg.setAttribute('class', 'h-5 w-5');
    svg.setAttribute('fill', 'none');
    svg.setAttribute('viewBox', '0 0 24 24');
    svg.setAttribute('stroke-width', '2');
    svg.setAttribute('stroke', 'currentColor');

    const path = document.createElementNS(ns, 'path');
    path.setAttribute('stroke-linecap', 'round');
    path.setAttribute('stroke-linejoin', 'round');

    if (type === 'success') {
        path.setAttribute('d', 'm4.5 12.75 6 6 9.75 4.5');
    } else if (type === 'error') {
        path.setAttribute('d', 'M6 18 18 6M6 6l12 12');
    } else if (type === 'warning') {
        path.setAttribute('d', 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z');
    } else {
        path.setAttribute('d', 'm11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z');
    }

    svg.appendChild(path);
    wrap.appendChild(svg);
    return wrap;
}

export const Toast = {
    container: null,

    init() {
        const existing = document.getElementById('toast-container');
        if (existing) {
            this.container = existing;
        } else {
            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            document.body.appendChild(this.container);
        }
        this.container.className = TOAST_CONTAINER_CLASS;
        this.container.setAttribute('aria-live', 'polite');
        this.container.setAttribute('aria-atomic', 'true');
    },

    show(message, type = 'success') {
        this.init();

        const style = TOAST_STYLES[type] || TOAST_STYLES.info;
        const toast = document.createElement('div');
        toast.setAttribute('role', 'alert');
        toast.className = `pointer-events-auto flex w-full items-start gap-3 rounded-xl border px-4 py-3 shadow-lg shadow-gray-200/80 transition-all duration-300 ${style.box}`;
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(1.5rem)';

        const text = document.createElement('p');
        text.className = 'min-w-0 flex-1 pt-1.5 text-sm font-medium leading-snug break-words';
        text.textContent = String(message);

        toast.appendChild(createToastIcon(type));
        toast.appendChild(text);
        this.container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
        });

        const remove = () => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(1.5rem)';
            setTimeout(() => toast.remove(), 280);
        };

        setTimeout(remove, TOAST_DURATION);
    },
};

export const Modal = {
    activeId: null,

    init() {
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activeId) this.close(this.activeId);
        });
        document.addEventListener('click', (e) => {
            const closeBtn = e.target.closest('[data-modal-close]');
            if (closeBtn?.dataset.modalClose) this.close(closeBtn.dataset.modalClose);
        });
    },

    open(id) {
        const el = document.getElementById(id);
        if (!el) return;
        this.activeId = id;
        const panel = el.querySelector('[data-modal-panel]');
        const backdrop = el.querySelector('[data-modal-backdrop]');
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(() => {
            backdrop?.classList.replace('opacity-0', 'opacity-100');
            panel?.classList.remove('opacity-0', 'scale-95');
            panel?.classList.add('opacity-100', 'scale-100');
        });
    },

    close(id) {
        const el = document.getElementById(id);
        if (!el) return;
        const panel = el.querySelector('[data-modal-panel]');
        const backdrop = el.querySelector('[data-modal-backdrop]');
        backdrop?.classList.replace('opacity-100', 'opacity-0');
        panel?.classList.remove('opacity-100', 'scale-100');
        panel?.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            el.classList.add('hidden');
            el.classList.remove('flex');
            if (this.activeId === id) {
                this.activeId = null;
                document.body.style.overflow = '';
            }
        }, 200);
    },
};

export const Loading = {
    overlay: null,

    init() {
        this.overlay = document.getElementById('loading-overlay');
    },

    show(message = 'Carregando...') {
        this.init();
        if (!this.overlay) return;
        const text = this.overlay.querySelector('[data-loading-text]');
        if (text) text.textContent = message;
        this.overlay.classList.remove('hidden');
        this.overlay.classList.add('flex');
    },

    hide() {
        if (!this.overlay) return;
        this.overlay.classList.add('hidden');
        this.overlay.classList.remove('flex');
    },

    async wrap(promise, message) {
        this.show(message);
        try {
            return await promise;
        } finally {
            this.hide();
        }
    },
};

export function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
}

export function formatDate(dateStr) {
    if (!dateStr) return '';
    const [y, m, d] = dateStr.split('-');
    return `${d}/${m}/${y}`;
}

export function delay(ms = 500) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

window.Toast = Toast;
window.Modal = Modal;
window.Loading = Loading;
