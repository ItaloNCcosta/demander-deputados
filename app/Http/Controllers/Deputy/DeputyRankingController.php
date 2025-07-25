<?php

declare(strict_types=1);

namespace App\Http\Controllers\Deputy;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Enums\StateEnum;
use App\Enums\PartyEnum;
use App\Http\Controllers\Controller;
use App\Services\Deputy\DeputyRankingService;

final class DeputyRankingController extends Controller
{
    public function index(Request $request, DeputyRankingService $rankingService): View
    {
        $filters = $request->only(['state', 'party', 'name']);
        $limit = (int) $request->get('limit', 10);
        $ranking = $rankingService->listTopByExpenses($filters, $limit);

        return view('deputies.ranking', [
            'ranking' => $ranking,
            'stateOptions' => StateEnum::cases(),
            'partyOptions' => PartyEnum::cases(),
        ]);
    }
}
