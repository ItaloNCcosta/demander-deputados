<?php

declare(strict_types=1);

namespace App\Services\DeputyExpense;

use App\Models\Deputy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final class DeputyExpenseListService
{
    public function listByDeputy(
        Deputy $deputy,
        array $filters = [],
        int $limit = 15
    ): LengthAwarePaginator {
        $query = $deputy
            ->expenses()
            ->when(
                !empty($filters['type']),
                fn(Builder $q) =>
                $q->where('expense_type', $filters['type'])
            )
            ->when(
                !empty($filters['date_start']),
                fn(Builder $q) =>
                $q->whereDate('document_date', '>=', $filters['date_start'])
            )
            ->when(
                !empty($filters['date_end']),
                fn(Builder $q) =>
                $q->whereDate('document_date', '<=', $filters['date_end'])
            )
            ->when(
                !empty($filters['month']),
                fn(Builder $q) =>
                $q->whereDate('month', '<=', $filters['month'])
            )
            ->when(
                !empty($filters['year']),
                fn(Builder $q) =>
                $q->whereDate('year', '<=', $filters['year'])
            )
            ->when(!empty($filters['order_by']), function (Builder $q) use ($filters) {
                match ($filters['order_by']) {
                    'document_date_asc'    => $q->orderBy('document_date', 'asc'),
                    'document_date_desc'   => $q->orderBy('document_date', 'desc'),
                    'document_amount_asc'  => $q->orderBy('document_amount', 'asc'),
                    'document_amount_desc' => $q->orderBy('document_amount', 'desc'),
                    default                => $q->orderBy('document_date', 'desc'),
                };
            })
            ->orderBy('document_date', 'desc');

        return $query->paginate($limit)
            ->withQueryString()
            ->appends($filters);
    }
}
