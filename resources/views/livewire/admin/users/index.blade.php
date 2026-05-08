<div class="flex flex-col gap-4">

    {{-- Page Header --}}
    <div class="overflow-hidden border-2 border-primary">
        <div class="px-4 py-2 bg-primary text-white text-sm font-bold uppercase tracking-wider">
            » Gerenciamento de Usuários
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="px-4 py-3 border-2 border-green-500 bg-green-50 text-green-700 text-sm font-medium">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="overflow-hidden border-2 border-primary">
        <div class="px-4 py-2 bg-primary text-white text-xs font-bold uppercase tracking-wider">
            » Filtros
        </div>
        <div class="p-4 bg-white flex flex-col sm:flex-row gap-3">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por nome, usuário ou email..."
                class="flex-1 px-3 py-2 text-sm border-2 border-primary-200 bg-primary-50 focus:outline-none focus:border-primary-400"
            />
            <select
                wire:model.live="statusFilter"
                class="px-3 py-2 text-sm border-2 border-primary-200 bg-primary-50 focus:outline-none focus:border-primary-400"
            >
                <option value="">Todos os status</option>
                <option value="pending">Pendente</option>
                <option value="approved">Aprovado</option>
                <option value="rejected">Rejeitado</option>
                <option value="banned">Banido</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden border-2 border-primary">
        <div class="px-4 py-2 bg-primary text-white text-xs font-bold uppercase tracking-wider flex justify-between">
            <span>» Usuários</span>
            <span>{{ $users->total() }} total</span>
        </div>
        <div class="bg-white overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-primary-100 bg-primary-50">
                        <th class="text-left px-4 py-2 font-bold text-primary-800">Nome</th>
                        <th class="text-left px-4 py-2 font-bold text-primary-800">Usuário</th>
                        <th class="text-left px-4 py-2 font-bold text-primary-800">Email</th>
                        <th class="text-left px-4 py-2 font-bold text-primary-800">Status</th>
                        <th class="text-left px-4 py-2 font-bold text-primary-800">Role</th>
                        <th class="text-left px-4 py-2 font-bold text-primary-800">Cadastro</th>
                        <th class="text-left px-4 py-2 font-bold text-primary-800">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-primary-50">
                            <td class="px-4 py-3 font-medium text-primary-900">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-primary-600">{{ '@' . $user->username }}</td>
                            <td class="px-4 py-3 text-primary-600">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'pending'  => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                        'approved' => 'bg-green-100 text-green-800 border-green-300',
                                        'rejected' => 'bg-red-100 text-red-800 border-red-300',
                                        'banned'   => 'bg-gray-100 text-gray-800 border-gray-300',
                                    ];
                                    $statusLabels = [
                                        'pending'  => 'Pendente',
                                        'approved' => 'Aprovado',
                                        'rejected' => 'Rejeitado',
                                        'banned'   => 'Banido',
                                    ];
                                    $statusValue = $user->status->value;
                                @endphp
                                <span class="inline-block px-2 py-0.5 text-xs font-bold border {{ $statusColors[$statusValue] ?? '' }}">
                                    {{ $statusLabels[$statusValue] ?? $statusValue }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($user->role->value === 'admin')
                                    <span class="inline-block px-2 py-0.5 text-xs font-bold border bg-primary-100 text-primary-800 border-primary-300">Admin</span>
                                @else
                                    <span class="inline-block px-2 py-0.5 text-xs font-bold border bg-gray-100 text-gray-600 border-gray-300">Usuário</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-primary-600 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @if ($user->status->value === 'pending' || $user->status->value === 'rejected')
                                        <button
                                            wire:click="approve({{ $user->id }})"
                                            wire:confirm="Aprovar {{ $user->name }}?"
                                            class="px-2 py-1 text-xs font-bold text-white bg-green-600 hover:bg-green-700 cursor-pointer"
                                        >Aprovar</button>
                                    @endif

                                    @if ($user->status->value === 'pending' || $user->status->value === 'approved')
                                        <button
                                            wire:click="reject({{ $user->id }})"
                                            wire:confirm="Rejeitar {{ $user->name }}?"
                                            class="px-2 py-1 text-xs font-bold text-white bg-red-500 hover:bg-red-600 cursor-pointer"
                                        >Rejeitar</button>
                                    @endif

                                    @if ($user->status->value !== 'banned')
                                        <button
                                            wire:click="ban({{ $user->id }})"
                                            wire:confirm="Banir {{ $user->name }}?"
                                            class="px-2 py-1 text-xs font-bold text-white bg-gray-700 hover:bg-gray-800 cursor-pointer"
                                        >Banir</button>
                                    @else
                                        <button
                                            wire:click="unban({{ $user->id }})"
                                            wire:confirm="Desbanir {{ $user->name }}?"
                                            class="px-2 py-1 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 cursor-pointer"
                                        >Desbanir</button>
                                    @endif

                                    @if ($user->role->value !== 'admin')
                                        <button
                                            wire:click="promoteToAdmin({{ $user->id }})"
                                            wire:confirm="Promover {{ $user->name }} a admin?"
                                            class="px-2 py-1 text-xs font-bold text-primary-800 bg-primary-100 hover:bg-primary-200 cursor-pointer"
                                        >↑ Admin</button>
                                    @else
                                        <button
                                            wire:click="demoteFromAdmin({{ $user->id }})"
                                            wire:confirm="Remover admin de {{ $user->name }}?"
                                            class="px-2 py-1 text-xs font-bold text-orange-800 bg-orange-100 hover:bg-orange-200 cursor-pointer"
                                        >↓ Revogar</button>
                                    @endif

                                    @if ($user->id !== auth()->id())
                                        <button
                                            wire:click="delete({{ $user->id }})"
                                            wire:confirm="Excluir permanentemente {{ $user->name }}? Esta ação não pode ser desfeita."
                                            class="px-2 py-1 text-xs font-bold text-white bg-red-800 hover:bg-red-900 cursor-pointer"
                                        >✕ Excluir</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-primary-500 text-sm">
                                Nenhum usuário encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="px-4 py-3 border-t-2 border-primary-100 bg-white">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

