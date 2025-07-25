<?php

declare(strict_types=1);

namespace App\Http\Controllers\Deputy;

use App\Enums\PartyEnum;
use App\Enums\StateEnum;
use App\Http\Controllers\Controller;
use App\Models\Deputy;
use App\Services\Deputy\DeputyListService;
use App\Services\Deputy\DeputyRankingService;
use App\Services\Deputy\DeputyShowService;
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
        $state = $state = StateEnum::cases();
        $party = PartyEnum::cases();

        return view('deputy.index', [
            'deputies' => $deputies,
            'state' => $state,
            'party' => $party,
            'ranking'  => $ranking,
        ]);
    }

    public function show(
        Request $request,
        Deputy $deputy,
        DeputyShowService $showService
    ): View {
        $filters = $request->only([
            'type',
            'date_start',
            'date_end',
            'order_by',
        ]);

        $data = $showService->handle($deputy, $filters);

        return view('deputy.show', [
            'deputy'   => $data['deputy'],
            'expenses' => $data['expenses'],
            'filters'  => $filters,
        ]);
    }
}
