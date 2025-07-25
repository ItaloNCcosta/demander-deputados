<?php

declare(strict_types=1);

namespace App\Http\Controllers\Expense;

use App\Enums\ExpenseTypeEnum;
use App\Http\Controllers\Controller;
use App\Services\Expense\ExpenseListService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ExpensesController extends Controller
{
    public function index(Request $request, ExpenseListService $service): View
    {
        $filters = $request->only(['start', 'end', 'type']);
        $expenses = $service->listByPeriod($filters);
        // $expenseType = ExpenseTypeEnum::cases();

        return view('expenses.index', [
            'expenses' => $expenses,
            'filters'  => $filters,
            // 'expenseType' => $expenseType
        ]);
    }
}
