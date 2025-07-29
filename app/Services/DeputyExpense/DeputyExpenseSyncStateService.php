<?php

declare(strict_types=1);

namespace App\Services\DeputyExpense;

use App\Models\Deputy;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

final class DeputyExpenseSyncStateService
{
    public function isStale(Deputy $deputy, int $minutes = 60): bool
    {
        $last = $deputy
            ->expenses()
            ->max('last_synced_at');

        Log::info('🕐 Verificando staleness', [
            'deputy_id' => $deputy->id,
            'last_synced_at' => $last,
            'minutes_ago' => now()->subMinutes($minutes),
            'is_stale' => !$last || Carbon::parse($last)->lt(now()->subMinutes($minutes))
        ]);

        if (!$last) {
            return true;
        }

        return Carbon::parse($last)->lt(now()->subMinutes($minutes));
    }
}
