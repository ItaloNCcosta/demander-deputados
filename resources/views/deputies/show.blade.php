<x-guest-layout title="{{ $deputy->name }}">
    <div class="">
        <section class="bg-emerald-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex items-center gap-6">
                <img src="{{ $deputy->photo_url }}" alt="Foto do deputado {{ $deputy->name }}"
                    class="w-28 h-28 rounded-full object-cover ring-4 ring-white/30">
                <div>
                    <h1 class="text-3xl font-extrabold leading-tight">{{ $deputy->name }}</h1>
                    <p class="text-emerald-100 mt-1">{{ $deputy->party_acronym }} •
                        {{ $deputy->state_code }} • {{ $deputy->email }}</p>
                </div>
            </div>
        </section>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ tab: 'perfil' }">
            <div class="flex gap-4 border-b border-slate-200/70 mb-8">
                <button @click="tab='perfil'"
                    :class="tab === 'perfil' ? 'border-emerald-600 text-emerald-700' :
                        'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-4 py-2 border-b-2 text-sm font-medium">Perfil</button>
                <button @click="tab='despesas'"
                    :class="tab === 'despesas' ? 'border-emerald-600 text-emerald-700' :
                        'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-4 py-2 border-b-2 text-sm font-medium">Despesas</button>
                <button @click="tab='contato'"
                    :class="tab === 'contato' ? 'border-emerald-600 text-emerald-700' :
                        'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-4 py-2 border-b-2 text-sm font-medium">Contato</button>
            </div>

            {{-- PERFIL --}}
            <section x-show="tab==='perfil'" x-cloak class="space-y-6">
                <div class="grid sm:grid-cols-2 gap-6">
                    {{-- Informações básicas --}}
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200/70">
                        <h3 class="text-sm font-semibold text-slate-500 uppercase mb-3">Informações básicas</h3>
                        <ul class="text-sm space-y-2">
                            <li>
                                <span class="text-slate-500">Nome civil:</span>
                                {{ $deputy->civil_name ?? '—' }}
                            </li>
                            <li>
                                <span class="text-slate-500">Nascimento:</span>
                                {{ optional($deputy->birth_date)->format('d/m/Y') ?? '—' }}
                            </li>
                            <li>
                                <span class="text-slate-500">Mandato atual:</span>
                                @if ($deputy->start_date && $deputy->end_date)
                                    {{ $deputy->start_date->format('Y') }}‑{{ $deputy->end_date->format('Y') }}
                                @else
                                    —
                                @endif
                            </li>
                            <li>
                                <span class="text-slate-500">Partido / UF:</span>
                                {{ $deputy->party_acronym }} ‑ {{ $deputy->state_code }}
                            </li>
                            <li>
                                <span class="text-slate-500">CPF:</span>
                                {{ $deputy->cpf ?? '—' }}
                            </li>
                        </ul>
                    </div>

                    {{-- Resumo financeiro --}}
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200/70">
                        <h3 class="text-sm font-semibold text-slate-500 uppercase mb-3">Resumo financeiro</h3>
                        @php
                            $total = $deputy->total_expenses;
                            $months =
                                $expenses
                                    ->map(fn($e) => \Illuminate\Support\Carbon::parse($e->date)->format('Y-m'))
                                    ->unique()
                                    ->count() ?:
                                1;
                            $average = $total / $months;
                            $lastDate = $expenses->sortByDesc('date')->first()->date ?? null;
                        @endphp
                        <ul class="text-sm space-y-2">
                            <li>
                                Total de despesas:
                                <strong>R$ {{ number_format($total, 2, ',', '.') }}</strong>
                            </li>
                            <li>
                                Média mensal:
                                <strong>R$ {{ number_format($average, 2, ',', '.') }}</strong>
                            </li>
                            <li>
                                Última despesa:
                                <strong>
                                    {{ $lastDate ? \Illuminate\Support\Carbon::parse($lastDate)->format('d/m/Y') : '—' }}
                                </strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            {{-- DESPESAS --}}
            <section x-show="tab==='despesas'" x-cloak class="space-y-6" id="despesas">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Despesas</h3>
                    <form class="flex gap-2 text-sm" method="GET">
                        <select name="type"
                            class="rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Tipo (todos)</option>
                            @foreach ($expenses->pluck('type')->unique() as $type)
                                <option value="{{ $type }}" @selected(request('type') === $type)>{{ $type }}
                                </option>
                            @endforeach
                        </select>
                        <select name="date_start"
                            class="rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Ano (todos)</option>
                            @foreach ($expenses->map(fn($e) => \Illuminate\Support\Carbon::parse($e->date)->year)->unique() as $year)
                                <option value="{{ $year }}" @selected(request('date_start') == (string) $year)>{{ $year }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded-md">Filtrar</button>
                    </form>
                </div>

                <div class="overflow-hidden rounded-xl border border-slate-200/70 shadow-sm bg-white">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium">Data</th>
                                <th class="px-4 py-3 text-left font-medium">Tipo</th>
                                <th class="px-4 py-3 text-left font-medium">Fornecedor</th>
                                <th class="px-4 py-3 text-right font-medium">Valor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($expenses as $expense)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3">
                                        {{ \Illuminate\Support\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">{{ $expense->type }}</td>
                                    <td class="px-4 py-3">{{ $expense->supplier }}</td>
                                    <td class="px-4 py-3 text-right">R$
                                        {{ number_format($expense->value, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-500">
                                        Nenhuma despesa encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- CONTATO --}}
            <section x-show="tab==='contato'" x-cloak class="space-y-6">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200/70">
                    <h3 class="text-sm font-semibold text-slate-500 uppercase mb-3">Canais oficiais</h3>
                    <ul class="text-sm space-y-2">
                        <li>
                            <span class="text-slate-500">Email:</span>
                            {{ $deputy->office_email ?? ($deputy->email ?? '—') }}
                        </li>
                        <li>
                            <span class="text-slate-500">Telefone:</span>
                            {{ $deputy->office_phone ?? '—' }}
                        </li>
                        @if ($deputy->website_url)
                            <li>
                                <span class="text-slate-500">Site:</span>
                                <a href="{{ $deputy->website_url }}" target="_blank"
                                    class="text-emerald-700 hover:underline">
                                    {{ $deputy->website_url }}
                                </a>
                            </li>
                        @endif
                        @if (!empty($deputy->social_links))
                            <li>
                                <span class="text-slate-500">Redes sociais:</span>
                                {{ implode(' • ', $deputy->social_links) }}
                            </li>
                        @endif
                    </ul>
                </div>
            </section>
        </main>
    </div>
</x-guest-layout>
