<?php

declare(strict_types=1);

namespace App\Services\Deputy;

use App\Models\Deputy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class DeputyRankingService
{
    public function __construct(protected Deputy $deputy)
    {
        $this->deputy = $deputy;
    }

    public function listTopByExpenses(array $filters = [], int $limit = 20): Collection
    {
        return $this->deputy
            ->newQuery()
            ->withSum('expenses', 'net_amount')
            ->when($filters['state'] ?? null, fn(Builder $query, $uf) => $query->where('state_code', $uf))
            ->when($filters['party'] ?? null, fn(Builder $query, $pty) => $query->where('party_acronym', $pty))
            ->when($filters['name'] ?? null, fn(Builder $query, $name) => $query->where('name', 'like', "%{$name}%"))
            ->orderByDesc('expenses_sum_net_amount')
            ->limit($limit)
            ->get();
    }
}
