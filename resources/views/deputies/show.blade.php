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

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ tab: '{{ request('tab', 'perfil') }}' }">
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
                            $total = $expenses->sum('net_amount');

                            $months = $expenses
                                ->map(fn($e) => \Illuminate\Support\Carbon::parse($e->document_date)->format('Y-m'))
                                ->unique()
                                ->count();
                            $months = $months ?: 1;

                            $average = $total / $months;

                            $last = $expenses->sortByDesc('document_date')->first();
                            $lastDate = $last
                                ? \Illuminate\Support\Carbon::parse($last->document_date)->format('d/m/Y')
                                : null;
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
                                <strong>{{ $lastDate ?? '—' }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            {{-- DESPESAS --}}
            <section x-show="tab==='despesas'" x-cloak class="space-y-6" id="despesas">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Despesas</h3>
                    <form action="{{ route('deputies.show', $deputy) }}" method="GET"
                        class="flex flex-wrap gap-2 text-sm items-end">
                        <input type="hidden" name="tab" value="despesas">

                        <div>
                            <label class="block text-xs text-slate-500">Ano</label>
                            <select name="year"
                                class="rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Todos</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" @selected(request('year') == $year)>
                                        {{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div>
                            <label class="block text-xs text-slate-500">Mês</label>
                            <select name="month"
                                class="rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Todos</option>
                                @foreach ($filterMonths as $m)
                                    <option value="{{ $m }}" @selected(($filters['month'] ?? '') == $m)>
                                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="flex gap-2">
                            <button type="submit"
                                class="px-3 py-1 bg-emerald-600 text-white rounded-md">Filtrar</button>

                            <a href="{{ route('deputies.show', $deputy) }}?tab=despesas"
                                class="text-slate-600 px-3 py-1 rounded-md hover:bg-slate-100">Limpar</a>
                        </div>
                    </form>

                </div>

                <div class="overflow-hidden rounded-xl border border-slate-200/70 shadow-sm bg-white">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium">Data documento</th>
                                <th class="px-4 py-3 text-left font-medium">Ano/Mês</th>
                                <th class="px-4 py-3 text-left font-medium">Documento/Tipo de Despesa</th>
                                <th class="px-4 py-3 text-left font-medium">Fornecedor</th>
                                <th class="px-4 py-3 text-right font-medium">Valor Líquido</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($expenses as $expense)
                                <tr class="hover:bg-slate-50">
                                    {{-- Data do documento --}}
                                    <td class="px-4 py-3">
                                        {{ \Illuminate\Support\Carbon::parse($expense->document_date)->format('d/m/Y') }}
                                    </td>

                                    {{-- Ano/Mês --}}
                                    <td class="px-4 py-3">
                                        {{ $expense->year }}/{{ str_pad($expense->month, 2, '0', STR_PAD_LEFT) }}
                                    </td>

                                    {{-- Informações do documento --}}
                                    <td class="px-4 py-3">
                                        <div class="font-semibold">#{{ $expense->document_number }}</div>
                                        <div class="text-xs text-slate-500">
                                            {{ $expense->document_type }}
                                            @if ($expense->document_url)
                                                · <a href="{{ $expense->document_url }}" target="_blank"
                                                    class="text-blue-600 underline">Ver</a>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Tipo de despesa --}}
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-700">
                                            {{ $expense->expense_type }}
                                        </div>
                                        <div class="text-sm text-slate-500">
                                            {{ $expense->supplier_name }}
                                        </div>
                                    </td>

                                    {{-- Valor líquido --}}
                                    <td class="px-4 py-3 text-right">
                                        R$ {{ number_format($expense->net_amount, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-slate-500">
                                        Nenhuma despesa encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-10 flex justify-center">
                        <x-pagination :paginator="$expenses" />
                    </div>
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
