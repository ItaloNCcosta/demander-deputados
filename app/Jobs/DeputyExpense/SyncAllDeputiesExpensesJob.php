<?php

declare(strict_types=1);

namespace App\Jobs\DeputyExpense;

use App\Models\Deputy;
use Illuminate\Bus\Batchable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;

final class SyncAllDeputiesExpensesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public function __construct()
    {
        $this->onQueue('sync_expenses');
    }

    public function handle(): void
    {
        Deputy::query()
            ->chunkById(100, function (Collection $deputies) {
                Bus::batch(
                    $deputies->map(fn(Deputy $d) => new SyncDeputyExpensesJob($d->external_id))
                )
                ->name('Sync Deputy Expenses Batch')
                ->onQueue('sync_expenses')
                ->dispatch();
            });
    }
}
