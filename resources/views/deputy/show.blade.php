<x-guest-layout title="{{ $deputy->name }}">
    <div class="max-w-7xl mx-auto">

        {{-- Botão “Voltar” --}}
        <div>
            <a href="{{ route('deputy.index') }}"
                class="py-4 inline-flex items-center text-indigo-600 hover:text-indigo-900">
                ← Voltar
            </a>
        </div>

        {{-- card deputy --}}
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center space-x-6">
                <img class="h-24 w-24 rounded-full object-cover" src="{{ $deputy->photo_url }}" alt="{{ $deputy->name }}">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $deputy->name }}</h1>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $deputy->party_acronym }} •
                        {{ $deputy->state_code }}</p>
                </div>
            </div>

            @php
                $totalExpenses = $expenses
                    ? $expenses->getCollection()->sum('document_amount')
                    : collect($expenses)->sum('document_amount');
            @endphp

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-green-50 p-4 rounded-lg flex items-center justify-between">
                    <span class="text-sm font-medium text-green-700">Gasto total</span>
                    <span class="text-xl font-bold text-green-900">
                        R$ {{ number_format($totalExpenses, 2, ',', '.') }}
                    </span>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg flex items-center justify-between">
                    <span class="text-sm font-medium text-blue-700">Última atualização</span>
                    <span class="text-xl font-bold text-blue-900">
                        {{ \Carbon\Carbon::parse($deputy->last_update)->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- List expenses --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Despesas</h2>

            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <form id="filter-form" method="GET" action="{{ route('deputy.show', $deputy) }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-6">

                    <div>
                        <label for="date_start" class="block text-sm font-medium text-gray-700">Data Início</label>
                        <input type="date" name="date_start" id="date_start"
                            value="{{ request('date_start') ? Carbon::parse(request('date_start'))->format('d/m/Y') : '' }}"
                            placeholder="dd/mm/aaaa"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm
                   focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>

                    <div>
                        <label for="date_end" class="block text-sm font-medium text-gray-700">Data Fim</label>
                        <input type="date" name="date_end" id="date_end" value="{{ request('date_end') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm
                   focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>

                    <div>
                        <label for="order_by" class="block text-sm font-medium text-gray-700">Ordenar por</label>
                        <select name="order_by" id="order_by"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Padrão</option>
                            <option value="document_date_desc" @if (request('order_by') === 'document_date_desc') selected @endif>
                                Data ↓
                            </option>
                            <option value="document_date_asc" @if (request('order_by') === 'document_date_asc') selected @endif>
                                Data ↑
                            </option>
                            <option value="document_amount_desc" @if (request('order_by') === 'document_amount_desc') selected @endif>
                                Valor ↓
                            </option>
                            <option value="document_amount_asc" @if (request('order_by') === 'document_amount_asc') selected @endif>
                                Valor ↑
                            </option>
                        </select>
                    </div>

                    <div class="md:col-span-2 flex items-end gap-2">
                        <button type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-4 py-2 rounded-lg">
                            Aplicar
                        </button>
                        <a href="{{ route('deputy.show', $deputy) }}"
                            class="text-slate-600 text-sm px-4 py-2 rounded-lg hover:bg-slate-100">
                            Limpar
                        </a>
                    </div>
                </form>
            </div>

            {{-- expenses --}}
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo de Despesa
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Valor
                            </th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($expenses as $expense)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $expense->expense_type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($expense->document_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                                    R$ {{ number_format($expense->document_amount, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ $expense->document_url }}" target="_blank"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        Ver documento
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Nenhuma despesa encontrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-10 flex justify-center">
                <x-pagination :paginator="$expenses" />
            </div>
        </div>
    </div>
</x-guest-layout>
