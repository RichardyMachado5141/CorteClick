<x-layouts.guest title="Cadastro">
    <div class="mx-auto w-full max-w-md">
        <div class="cc-card p-8 shadow-lg shadow-gray-200/60 sm:p-10">
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-ink">Criar conta</h1>
                <p class="mt-2 text-sm text-ink-muted">Comece a agendar na melhor barbearia</p>
            </div>

            <form action="{{ route('perfil') }}" method="GET" class="space-y-4" data-toast="Conta criada com sucesso!">
                <x-input label="Nome completo" name="name" placeholder="Seu nome" icon="user" />
                <x-input label="E-mail" name="email" type="email" placeholder="seu@email.com" icon="mail" />
                <x-input label="Telefone" name="phone" placeholder="(11) 99999-9999" icon="phone" />
                <x-input label="Senha" name="password" type="password" placeholder="••••••••" icon="lock" />
                <x-button type="submit" class="w-full">Cadastrar</x-button>
            </form>

            <p class="mt-6 text-center text-sm text-ink-muted">
                Já tem conta? <a href="{{ route('login') }}" class="font-semibold text-wine">Entrar</a>
            </p>
        </div>
    </div>
</x-layouts.guest>
