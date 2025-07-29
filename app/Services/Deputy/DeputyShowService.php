<?php

declare(strict_types=1);

namespace App\Services\Deputy;

use App\Jobs\DeputyExpense\SyncDeputyExpensesJob;
use App\Models\Deputy;
use App\Services\DeputyExpense\DeputyExpenseListService;
use App\Services\DeputyExpense\DeputyExpenseSyncStateService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

final class DeputyShowService
{
    public function __construct(
        private readonly DeputyExpenseListService $expenseListService,
        private readonly DeputyExpenseSyncStateService $syncStateService,
        private readonly ShowDeputyByIdApiService $apiService,
        private readonly DeputyUpsertService $upsertService,
    ) {}

    public function handle(Deputy $deputy, array $filters = [], bool $withExpenses = true): array
    {
        $isEmpty = $deputy->expenses()->count() === 0;
        $isStale = $this->syncStateService->isStale($deputy);

        Log::info('🔍 Verificando sincronização', [
            'deputy_id' => $deputy->id,
            'isEmpty' => $isEmpty,
            'isStale' => $isStale,
            'withExpenses' => $withExpenses
        ]);

        if ($withExpenses && ($isEmpty || $isStale)) {
            Log::info('🚀 Disparando SyncDeputyExpensesJob', [
                'deputy_external_id' => $deputy->external_id
            ]);

            SyncDeputyExpensesJob::dispatchSync($deputy->external_id);

            Log::info('✅ Job executado');
        } else {
            Log::info('⏭️ Sincronização pulada - condições não atendidas');
        }

        $apiData = $this->apiService->showById($deputy->external_id);
        $this->upsertService->upsertByExternalId($apiData ?? $apiData);

        $deputy->refresh();

        $expenses = $withExpenses
            ? $this->expenseListService->listByDeputy($deputy, $filters, 50)
            : [];

        return [
            'deputy'   => $deputy,
            'expenses' => $expenses,
        ];
    }
}
