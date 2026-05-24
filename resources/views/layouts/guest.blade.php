<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CorteClick' }} — Agendamento para Barbearias</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -left-32 top-0 h-96 w-96 rounded-full bg-brand-red/20 blur-3xl"></div>
            <div class="absolute -right-32 bottom-0 h-96 w-96 rounded-full bg-brand-red/10 blur-3xl"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-zinc-900 via-surface to-surface"></div>
        </div>

        <div class="relative z-10 flex min-h-screen flex-col">
            @isset($showHeader)
                <header class="px-6 py-6">
                    <x-logo />
                </header>
            @endisset

            <main class="flex flex-1 items-center justify-center px-4 py-8">
                {{ $slot }}
            </main>

            <footer class="px-6 py-4 text-center text-xs text-zinc-600">
                &copy; {{ date('Y') }} CorteClick. Todos os direitos reservados.
            </footer>
        </div>
    </div>
</body>
</html>
