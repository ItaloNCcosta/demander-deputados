<?php

declare(strict_types=1);

namespace App\Services\Deputy;

use App\Models\Deputy;
use App\Services\DeputyExpense\DeputyExpenseListService;

final class DeputyShowService
{
    public function __construct(
        private readonly DeputyExpenseListService $expenseListService
    ) {}

    public function handle(Deputy $deputy, array $filters = [], bool $withExpenses = true): array
    {
        $expenses = [];

        if ($withExpenses) {
            $expenses = $this->expenseListService->listByDeputy($deputy, $filters);
        }

        return [
            'deputy'   => $deputy,
            'expenses' => $expenses,
        ];
    }
}
