<?php

declare(strict_types=1);

namespace App\Jobs\DeputyExpense;

use App\Models\DeputyExpense;
use App\Services\DeputyExpense\DeputyExpenseUpsertService;
use App\Services\DeputyExpense\GetDeputyExpensesApiService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

final class SyncDeputyExpensesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public readonly int $externalDeputyId)
    {
        $this->onQueue('sync_expenses');
    }

    public function handle(
        GetDeputyExpensesApiService $api,
        DeputyExpenseUpsertService $upsert
    ): void {
        $lastDate = DeputyExpense::whereRelation('deputy', 'external_id', $this->externalDeputyId)
            ->max('document_date');

        $filters = $lastDate ? [
            'dataInicial' => $lastDate,
            'dataFinal'   => now()->format('Y-m-d'),
        ] : [];

        $response = $api->list($this->externalDeputyId, $filters);

        $this->processPage($upsert, $response['dados'] ?? []);

        $links = $response['links'] ?? [];
        while ($next = collect($links)->firstWhere('rel', 'next')) {
            $response = $api->listByUrl($next['href']);
            $this->processPage($upsert, $response['dados'] ?? []);
            $links = $response['links'] ?? [];
        }
    }

    private function processPage(DeputyExpenseUpsertService $upsert, array $expenses): void
    {
        foreach ($expenses as $expense) {
            $upsert->upsert($this->externalDeputyId, $expense);
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::error("SyncDeputyExpensesJob failed for {$this->externalDeputyId}", [
            'message' => $e->getMessage(),
        ]);
    }
}
