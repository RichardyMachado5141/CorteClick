/** Seletor no documento inteiro (modais, overlays, etc.) */
export function $(selector) {
    return document.querySelector(selector);
}

export function $id(id) {
    return document.getElementById(id);
}

/** Seletor restrito a um container da página */
export function $in(root, selector) {
    return root?.querySelector(selector) ?? null;
}

export function $$in(root, selector) {
    return root ? [...root.querySelectorAll(selector)] : [];
}
