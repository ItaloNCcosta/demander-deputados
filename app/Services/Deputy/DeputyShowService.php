<?php

declare(strict_types=1);

namespace App\Services\Deputy;

use App\Jobs\DeputyExpense\SyncDeputyExpensesJob;
use App\Models\Deputy;
use App\Services\DeputyExpense\DeputyExpenseListService;
use App\Services\DeputyExpense\DeputyExpenseSyncStateService;
use Illuminate\Pagination\LengthAwarePaginator;

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

        if (
            $withExpenses && $isEmpty
            || $this->syncStateService->isStale($deputy)
        ) {
            SyncDeputyExpensesJob::dispatchSync($deputy->external_id);
        }

        $apiData = $this->apiService->showById($deputy->external_id);
        $this->upsertService->upsertByExternalId($apiData ?? $apiData);

        $deputy->refresh();

        $expenses = $withExpenses
            ? $this->expenseListService->listByDeputy($deputy, $filters)
            : new LengthAwarePaginator([], 0, 15);

        return [
            'deputy'   => $deputy,
            'expenses' => $expenses,
        ];
    }
}
