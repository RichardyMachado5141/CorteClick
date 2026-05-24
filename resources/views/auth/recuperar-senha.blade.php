<x-layouts.guest title="Recuperar senha">
    <div class="mx-auto w-full max-w-md">
        <div class="cc-card p-8 shadow-lg shadow-gray-200/60 sm:p-10">
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-ink">Recuperar senha</h1>
                <p class="mt-2 text-sm text-ink-muted">Enviaremos um link para redefinir sua senha</p>
            </div>

            <form action="{{ route('login') }}" method="GET" class="space-y-5" data-toast="Link enviado! Verifique seu e-mail.">
                <x-input label="E-mail ou telefone" name="login" placeholder="seu@email.com" icon="mail" />
                <x-button type="submit" class="w-full">Enviar link</x-button>
            </form>

            <p class="mt-6 text-center text-sm">
                <a href="{{ route('login') }}" class="font-medium text-wine hover:text-wine-dark">← Voltar ao login</a>
            </p>
        </div>
    </div>
</x-layouts.guest>
