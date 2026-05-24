<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CorteClick' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface">
    <div class="flex min-h-screen">
        <x-sidebar :role="$role ?? 'cliente'" :user-name="$userName ?? 'Usuário Demo'" />

        <div class="flex min-w-0 flex-1 flex-col lg:pl-72">
            <x-navbar :title="$headerTitle ?? ''" :subtitle="$headerSubtitle ?? null" />

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('modals')
    @stack('scripts')
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar')?.classList.toggle('-translate-x-full');
            document.getElementById('sidebar-overlay')?.classList.toggle('hidden');
        }
    </script>
</body>
</html>
