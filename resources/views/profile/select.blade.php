<x-layouts.guest title="Selecionar perfil" page="perfil">
    <div class="mx-auto w-full max-w-4xl px-4">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-ink">Como deseja acessar?</h1>
            <p class="mt-2 text-ink-muted">Olá, <span data-user-name class="font-semibold text-wine">Usuário</span> — escolha seu perfil</p>
        </div>

        <div class="grid gap-5 sm:grid-cols-3">
            @foreach($perfis as $perfil)
                <a
                    href="{{ route($perfil['rota']) }}"
                    data-perfil="{{ $perfil['slug'] }}"
                    class="cc-card-hover group block p-8 text-center"
                >
                    <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-wine-light text-wine transition duration-300 group-hover:bg-wine group-hover:text-white group-hover:shadow-lg group-hover:shadow-wine/20">
                        <x-icon :name="$perfil['icone']" class="h-7 w-7" />
                    </div>
                    <h2 class="text-lg font-bold text-ink">{{ $perfil['titulo'] }}</h2>
                    <p class="mt-2 text-sm text-ink-muted">{{ $perfil['descricao'] }}</p>
                    <span class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-wine opacity-0 transition group-hover:opacity-100">
                        Acessar <x-icon name="chevron-right" class="h-4 w-4" />
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</x-layouts.guest>
