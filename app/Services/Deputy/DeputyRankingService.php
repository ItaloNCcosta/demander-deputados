<?php

declare(strict_types=1);

namespace App\Services\Deputy;

use App\Models\Deputy;
use Illuminate\Database\Eloquent\Collection;

final class DeputyRankingService
{
    public function __construct(protected Deputy $deputy)
    {
        $this->deputy = $deputy;
    }

    public function listTopByExpenses(int $limit = 5): Collection
    {
        return $this->deputy->withSum('expenses', 'net_amount')
            ->orderByDesc('expenses_sum_net_amount')
            ->limit($limit)
            ->get();
    }
}
