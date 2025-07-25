<?php

declare(strict_types=1);

namespace App\Services\Expense;

use App\Models\DeputyExpense;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class ExpenseListService
{
    public function __construct(protected DeputyExpense $deputyExpense)
    {
        $this->deputyExpense = $deputyExpense;
    }

    public function listByPeriod(array $filters = []): Collection
    {
        return $this->deputyExpense->newQuery()
            ->when(
                $filters['start'] ?? null,
                fn(Builder $q, $start) =>
                $q->whereDate('document_date', '>=', $start)
            )
            ->when(
                $filters['end'] ?? null,
                fn(Builder $q, $end) =>
                $q->whereDate('document_date', '<=', $end)
            )
            ->when(
                $filters['type'] ?? null,
                fn(Builder $q, $type) =>
                $q->where('expense_type', $type)
            )
            ->orderByDesc('document_date')
            ->get();
    }
}
