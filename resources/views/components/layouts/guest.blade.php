@props(['title' => 'CorteClick', 'showHeader' => true, 'page' => null])

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — CorteClick</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface" @if($page) data-page="{{ $page }}" @endif>
    <div id="toast-container" class="fixed right-4 top-4 z-[200] flex flex-col items-end gap-3 pointer-events-none max-w-sm"></div>

    <div id="loading-overlay" class="fixed inset-0 z-[90] hidden items-center justify-center bg-white/70 backdrop-blur-sm">
        <div class="flex flex-col items-center gap-4 rounded-2xl bg-white px-8 py-6 shadow-xl">
            <div class="cc-spinner"></div>
            <p data-loading-text class="text-sm font-medium text-ink-muted">Carregando...</p>
        </div>
    </div>

    <div class="flex min-h-screen flex-col">
        @if($showHeader)
            <header class="border-b border-border bg-white px-6 py-5">
                <x-logo />
            </header>
        @endif

        <main class="flex flex-1 items-center justify-center px-4 py-10">
            <div class="w-full animate-fade-in">{{ $slot }}</div>
        </main>

        <footer class="border-t border-border bg-white py-4 text-center text-xs text-ink-muted">
            &copy; {{ date('Y') }} CorteClick — Agendamento para barbearias
        </footer>
    </div>
</body>
</html>
