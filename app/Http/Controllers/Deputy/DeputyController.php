<?php

declare(strict_types=1);

namespace App\Http\Controllers\Deputy;

use App\Enums\PartyEnum;
use App\Enums\StateEnum;
use App\Http\Controllers\Controller;
use App\Jobs\DeputyExpense\SyncDeputyExpensesJob;
use App\Models\Deputy;
use App\Services\Deputy\DeputyListService;
use App\Services\Deputy\DeputyRankingService;
use App\Services\Deputy\DeputyShowService;
use App\Services\DeputyExpense\DeputyExpenseSyncStateService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DeputyController extends Controller
{
    public function index(
        Request $request,
        DeputyListService $deputyListService,
        DeputyRankingService $deputyRankingService
    ): View {
        $deputies = $deputyListService->listByFilters($request->all());
        $ranking = $deputyRankingService->listTopByExpenses();
        $state = StateEnum::cases();
        $party = PartyEnum::cases();

        return view('deputies.index', [
            'deputies' => $deputies,
            'state' => $state,
            'party' => $party,
            'ranking'  => $ranking,
        ]);
    }

    public function show(
        Request $request,
        Deputy $deputy,
        DeputyShowService $showService,
        DeputyExpenseSyncStateService $syncState
    ): View {
        $filters = $request->only([
            'type',
            'date_start',
            'date_end',
            'order_by',
            'month',
            'year'
        ]);

        $data = $showService->handle($deputy, $filters);

        if ($syncState->isStale($deputy, 60)) {
            SyncDeputyExpensesJob::dispatch($deputy->external_id);
        }

        $years  = $deputy->expenses()->distinct()->orderByDesc('year')->pluck('year');
        $months = $deputy->expenses()->distinct()->orderBy('month')->pluck('month');

        return view('deputies.show', [
            'deputy'   => $data['deputy'],
            'expenses' => $data['expenses'],
            'years'  => $years,
            'months' => $months,
        ]);
    }
}
