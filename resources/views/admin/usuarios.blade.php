<x-layouts.app
    role="admin"
    user-name="Administrador"
    title="Usuários — CorteClick"
    header-title="Usuários"
    header-subtitle="Gerencie contas do sistema"
    page="admin-usuarios"
    :page-data="$pageData"
>
    <div id="admin-usuarios">
        <x-card class="mb-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end">
                <div class="relative flex-1">
                    <x-icon name="search" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-light" />
                    <input id="busca" type="search" placeholder="Buscar por nome, e-mail ou telefone..." class="cc-input pl-10" />
                </div>
                <select id="filtro-perfil" class="cc-input lg:w-44">
                    <option value="">Todos os perfis</option>
                    <option>Cliente</option>
                    <option>Profissional</option>
                    <option>Admin</option>
                </select>
                <select id="filtro-status" class="cc-input lg:w-40">
                    <option value="">Todos status</option>
                    <option value="ativo">Ativos</option>
                    <option value="inativo">Inativos</option>
                </select>
            </div>
            <p id="contador" class="mt-3 text-sm text-ink-muted"></p>
        </x-card>

        <x-card :padding="false" class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-border bg-gray-50">
                            <th class="px-6 py-3.5 font-semibold text-ink-muted">Usuário</th>
                            <th class="px-6 py-3.5 font-semibold text-ink-muted">Contato</th>
                            <th class="px-6 py-3.5 font-semibold text-ink-muted">Perfil</th>
                            <th class="px-6 py-3.5 font-semibold text-ink-muted">Cadastro</th>
                            <th class="px-6 py-3.5 font-semibold text-ink-muted">Status</th>
                            <th class="px-6 py-3.5 font-semibold text-ink-muted">Ativo</th>
                        </tr>
                    </thead>
                    <tbody id="usuarios-tbody"></tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.app>
