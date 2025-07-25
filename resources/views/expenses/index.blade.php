<x-guest-layout title="Despesas">
    <section class="bg-gradient-to-tr from-slate-800 to-slate-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
            <h1 class="text-3xl md:text-4xl font-extrabold">Gastos dos Deputados</h1>
            <p class="mt-2 text-slate-200 text-sm md:text-base">
                Explore e filtre despesas por período, tipo, fornecedor e valor.
            </p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ open: true }">
        <form method="GET" action="{{ route('expenses.index') }}"
            class="grid grid-cols-1 md:grid-cols-8 gap-4 bg-white p-4 rounded-xl shadow-sm border border-slate-200/70 mb-8"
            :class="{ 'hidden': !open, 'block': open }">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                    Período inicial
                </label>
                <input type="date" name="start" value="{{ $filters['start'] ?? '' }}"
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wide">
                    Período final
                </label>
                <input type="date" name="end" value="{{ $filters['end'] ?? '' }}"
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-4 py-2 rounded-lg">
                    Aplicar
                </button>
                <a href="{{ route('expenses.index') }}"
                    class="text-slate-600 text-sm px-4 py-2 rounded-lg hover:bg-slate-100">
                    Limpar
                </a>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl border border-slate-200/70 shadow-sm bg-white">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Data</th>
                        <th class="px-4 py-3 text-left font-medium">Deputado</th>
                        <th class="px-4 py-3 text-left font-medium">Tipo</th>
                        <th class="px-4 py-3 text-left font-medium">Fornecedor</th>
                        <th class="px-4 py-3 text-right font-medium">Valor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                {{ \Illuminate\Support\Carbon::parse($expense->document_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('deputies.show', $expense->deputy) }}"
                                    class="text-emerald-700 hover:underline">
                                    {{ $expense->deputy->name }}
                                </a>
                            </td>
                            <td class="px-4 py-3">{{ $expense->type_label }}</td>
                            <td class="px-4 py-3">{{ $expense->supplier_name }}</td>
                            <td class="px-4 py-3 text-right">
                                R$ {{ number_format($expense->document_amount, 2, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                                Nenhuma despesa encontrada para este período.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-10 flex justify-center">
            <x-pagination :paginator="$expenses" />
        </div>
    </section>
</x-guest-layout>
