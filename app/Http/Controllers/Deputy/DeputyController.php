<?php

namespace App\Http\Controllers\Deputy;

use App\Enums\PartyEnum;
use App\Enums\StateEnum;
use App\Http\Controllers\Controller;
use App\Services\Deputy\DeputyListService;
use App\Services\Deputy\DeputyRankingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeputyController extends Controller
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
}
