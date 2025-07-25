<?php

declare(strict_types=1);

namespace App\Services\DeputyExpense;

use App\Models\Deputy;
use Illuminate\Support\Carbon;

final class DeputyExpenseSyncStateService
{
    public function isStale(Deputy $deputy, int $minutes = 60): bool
    {
        $last = $deputy
            ->expenses()
            ->max('last_synced_at');

        if (! $last) {
            return true;
        }

        return Carbon::parse($last)->lt(now()->subMinutes($minutes));
    }
}
