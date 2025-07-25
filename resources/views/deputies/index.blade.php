<x-guest-layout title="Página Inicial">
    <section class="bg-gradient-to-br from-emerald-600 to-emerald-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 text-center">
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight">
                Consulte Deputados e Despesas em <span class="underline decoration-white/40">1 clique</span>
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-emerald-50 text-sm md:text-base">
                Filtre por nome, estado, partido ou tipo de despesa. Dados oficiais, explicados de forma simples.
            </p>
            <form action="{{ route('deputies.index') }}" id="buscar" class="mt-8 max-w-2xl mx-auto" method="GET">
                <div class="flex rounded-xl overflow-hidden shadow-lg">
                    <div class="relative flex-1">
                        <input id="searchInput" name="name" type="text" value="{{ request('name') }}"
                            placeholder="Digite o nome do deputado…"
                            class="w-full px-4 py-4 text-sm bg-slate-900/90 text-white focus:outline-none" />

                        @if (request('name'))
                            <a href="{{ route('deputies.index') }}"
                                class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-200"
                                aria-label="Limpar busca">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                        class="bg-slate-900/90 px-6 py-3 text-sm md:text-base font-semibold hover:bg-slate-900 transition">
                        Buscar
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="bg-white border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <p class="text-sm text-slate-600">Refinar resultados</p>
            <button class="md:hidden text-emerald-600 text-sm font-medium inline-flex items-center gap-1">
                <span x-text="open ? 'Fechar filtros' : 'Filtros'"></span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-4">
            <form method="GET" action="{{ route('deputies.index') }}"
                class="grid grid-cols-1 md:grid-cols-6 gap-4 md:gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                        Estado
                    </label>
                    <select name="state"
                        class="w-full rounded-lg border-slate-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Todos</option>
                        @foreach ($state as $item)
                            <option value="{{ $item->value }}" @selected(request('state') === $item->value)>{{ $item->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                        Partido
                    </label>
                    <select name="party"
                        class="w-full rounded-lg border-slate-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Todos</option>
                        @foreach ($party as $item)
                            <option value="{{ $item->value }}" @selected(request('party') === $item->value)>{{ $item->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 flex items-end gap-2">
                    <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-4 py-2 rounded-lg">
                        Aplicar
                    </button>
                    <a href="{{ route('deputies.index') }}"
                        class="text-slate-600 text-sm px-4 py-2 rounded-lg hover:bg-slate-100">
                        Limpar
                    </a>
                </div>
            </form>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h2 class="text-xl font-semibold mb-6">Resultados ({{ $deputies->total() }})</h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($deputies as $deputy)
                <article class="bg-white rounded-xl shadow-sm border border-slate-200/70 p-4 flex flex-col">
                    <div class="flex items-center gap-3 mb-3">
                        <img loading="lazy" src="{{ $deputy->photo_url ?: 'https://via.placeholder.com/64' }}"
                            alt="Foto de {{ $deputy->name }}" class="w-14 h-14 rounded-full object-cover" />
                        <div>
                            <h3 class="font-semibold text-slate-900 leading-tight">
                                <a href="{{ route('deputies.show', $deputy) }}">{{ $deputy->name }}</a>
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $deputy->party_acronym }} •
                                {{ $deputy->state_code }}</p>
                        </div>
                    </div>
                    <dl class="grid grid-cols-2 gap-3 text-xs text-slate-600 flex-1">
                        <div>
                            <dt class="font-semibold text-slate-500">Gasto total</dt>
                            <dd class="text-slate-800">R$
                                {{ number_format($deputy->expenses->sum('net_amount'), 2, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Última atualização</dt>
                            <dd class="text-slate-800">{{ optional($deputy->expenses->max('last_synced_at'))->format('d/m/Y') }}
                            </dd>
                        </div>
                    </dl>
                </article>
            @empty
                <p class="col-span-full text-center text-slate-600">Nenhum resultado encontrado.</p>
            @endforelse
        </div>

        <div class="mt-10 flex justify-center">
            <x-pagination :paginator="$deputies" />
        </div>
    </main>
</x-guest-layout>
