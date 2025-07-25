<x-guest-layout title='Ranking'>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold mb-8">Ranking de Gastos</h1>

        <form method="GET" action="{{ route('deputies.ranking') }}"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div>
                <label for="state" class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                <select id="state" name="state"
                    class="block w-full rounded-md border border-slate-300 bg-white py-2 px-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Todos</option>
                    @foreach ($stateOptions as $case)
                        <option value="{{ $case->value }}" @selected(request('state') === $case->value)>
                            {{ $case->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="party" class="block text-sm font-medium text-slate-700 mb-1">Partido</label>
                <select id="party" name="party"
                    class="block w-full rounded-md border border-slate-300 bg-white py-2 px-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Todos</option>
                    @foreach ($partyOptions as $case)
                        <option value="{{ $case->value }}" @selected(request('party') === $case->value)>
                            {{ $case->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nome</label>
                <input id="name" type="text" name="name" placeholder="Procure por nome"
                    value="{{ request('name', '') }}"
                    class="block w-full rounded-md border border-slate-300 bg-white py-2 px-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
            </div>

            <div>
                <label for="limit" class="block text-sm font-medium text-slate-700 mb-1">Registros</label>
                <input id="limit" type="number" name="limit" min="1" max="100" step="1"
                    value="{{ request('limit', 10) }}"
                    class="block w-full rounded-md border border-slate-300 bg-white py-2 px-3 text-center shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    title="Número de registros" />
            </div>

            <div class="flex items-end">
                <button type="submit"
                    class="w-full py-2 bg-emerald-600 text-white font-medium rounded-md shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    Filtrar
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl border border-slate-200/70 shadow-sm bg-white">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Deputado</th>
                        <th class="px-4 py-3 text-left font-medium">Estado</th>
                        <th class="px-4 py-3 text-left font-medium">Partido</th>
                        <th class="px-4 py-3 text-right font-medium">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($ranking as $idx => $deputy)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('deputies.show', $deputy) }}" class="text-emerald-700 hover:underline">
                                    {{ $deputy->name }}
                                </a>
                            </td>
                            <td class="px-4 py-3">{{ $deputy->state_code }}</td>
                            <td class="px-4 py-3">{{ $deputy->party_acronym }}</td>
                            <td class="px-4 py-3 text-right">
                                R$ {{ number_format($deputy->expenses_sum_net_amount, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</x-guest-layout>
