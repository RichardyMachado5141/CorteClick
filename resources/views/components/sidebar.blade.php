@props(['role' => 'cliente', 'userName' => 'Usuário'])

@php
    $menus = [
        'cliente' => [
            ['route' => 'cliente.dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
            ['route' => 'cliente.agendamentos', 'label' => 'Meus Agendamentos', 'icon' => 'calendar'],
        ],
        'profissional' => [
            ['route' => 'profissional.dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
            ['route' => 'profissional.servicos', 'label' => 'Meus Serviços', 'icon' => 'scissors'],
        ],
        'admin' => [
            ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'chart'],
            ['route' => 'admin.usuarios', 'label' => 'Usuários', 'icon' => 'users'],
            ['route' => 'admin.agendamentos', 'label' => 'Agendamentos', 'icon' => 'clipboard'],
        ],
    ];
    $items = $menus[$role] ?? $menus['cliente'];
    $roleLabel = match($role) {
        'cliente' => 'Cliente',
        'profissional' => 'Profissional',
        'admin' => 'Administrador',
        default => 'Usuário',
    };
    $initials = strtoupper(substr($userName, 0, 2));
@endphp

<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col border-r border-border bg-white transition-transform duration-300 lg:translate-x-0">
    <div class="flex h-16 items-center border-b border-border px-5">
        <x-logo size="sm" />
    </div>

    <div class="border-b border-border p-4">
        <div class="flex items-center gap-3 rounded-xl bg-gray-50 p-3">
            <div data-user-initials class="flex h-10 w-10 items-center justify-center rounded-full bg-wine text-sm font-bold text-white">{{ $initials }}</div>
            <div class="min-w-0 flex-1">
                <p data-user-name class="truncate text-sm font-semibold text-ink">{{ $userName }}</p>
                <p class="text-xs text-ink-muted">{{ $roleLabel }}</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto p-3">
        @foreach($items as $item)
            <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'cc-nav-link-active' : 'cc-nav-link' }}">
                <x-icon :name="$item['icon']" class="h-5 w-5" />
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="space-y-1 border-t border-border p-3">
        <a href="{{ route('perfil') }}" data-trocar-perfil class="cc-nav-link">
            <x-icon name="user" class="h-5 w-5" />
            Trocar perfil
        </a>
        <a href="{{ route('login') }}" data-logout class="cc-nav-link text-red-600 hover:bg-red-50 hover:text-red-700">
            <x-icon name="logout" class="h-5 w-5" />
            Sair
        </a>
    </div>
</aside>

<div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-ink/30 backdrop-blur-sm lg:hidden" onclick="toggleSidebar()"></div>
