@props([
    'role' => 'cliente',
    'userName' => 'Usuário Demo',
    'headerTitle' => '',
    'headerSubtitle' => null,
    'title' => 'CorteClick',
    'page' => null,
    'pageData' => [],
])

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface" @if($page) data-page="{{ $page }}" @endif>
    <div id="loading-overlay" class="fixed inset-0 z-[90] hidden items-center justify-center bg-white/70 backdrop-blur-sm">
        <div class="flex flex-col items-center gap-4 rounded-2xl bg-white px-8 py-6 shadow-xl">
            <div class="cc-spinner"></div>
            <p data-loading-text class="text-sm font-medium text-ink-muted">Carregando...</p>
        </div>
    </div>

    <div id="toast-container" class="fixed right-4 top-4 z-[200] flex flex-col items-end gap-3 pointer-events-none w-full max-w-sm px-4 sm:px-0 sm:w-auto" aria-live="polite"></div>

    @if($page)
        <script id="page-data" type="application/json">@json($pageData)</script>
    @endif

    <div class="flex min-h-screen">
        <x-sidebar :role="$role" :user-name="$userName" />
        <div class="flex min-w-0 flex-1 flex-col lg:pl-64">
            <x-navbar :title="$headerTitle" :subtitle="$headerSubtitle" />
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('modals')

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar')?.classList.toggle('-translate-x-full');
            document.getElementById('sidebar-overlay')?.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
