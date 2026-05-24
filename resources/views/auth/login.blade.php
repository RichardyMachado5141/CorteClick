<x-layouts.guest title="Entrar" page="login">
    <div class="mx-auto w-full max-w-md">
        <div class="cc-card p-8 shadow-lg shadow-gray-200/60 sm:p-10">
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-ink">Bem-vindo de volta</h1>
                <p class="mt-2 text-sm text-ink-muted">Entre com seu e-mail para continuar</p>
            </div>

            <form id="login-form" method="GET" class="space-y-5">
                <x-input label="E-mail" name="login" type="email" placeholder="Digite seu email" icon="mail" autocomplete="username" />
                <x-input label="Senha" name="password" type="password" placeholder="••••••••" icon="lock" autocomplete="current-password" />

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-ink-muted">
                        <input type="checkbox" class="rounded border-border text-wine focus:ring-wine/30">
                        Lembrar-me
                    </label>
                    <a href="{{ route('recuperar-senha') }}" class="font-medium text-wine hover:text-wine-dark transition">Esqueci a senha</a>
                </div>

                <x-button type="submit" class="w-full">Entrar</x-button>
            </form>

            <p class="mt-6 text-center text-sm text-ink-muted">
                Não tem conta? <a href="{{ route('cadastro') }}" class="font-semibold text-wine hover:text-wine-dark">Cadastre-se</a>
            </p>
        </div>
    </div>
</x-layouts.guest>
