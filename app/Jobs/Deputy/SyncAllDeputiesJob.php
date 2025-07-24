<?php

namespace App\Jobs\Deputy;

use App\Services\Deputy\DeputyUpsertService;
use App\Services\Deputy\GetAllDeputyApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncAllDeputiesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        $this->onQueue('sync_deputies');
    }

    public function handle(
        GetAllDeputyApiService $getAllDeputyApiService,
        DeputyUpsertService $deputyUpsertService
    ): void {
        $response = $getAllDeputyApiService->list();

        foreach ($response['dados'] as $row) {
            $deputyUpsertService->upsertOne($row);
        }

        $links = $response['links'] ?? [];
        while ($next = collect($links)->firstWhere('rel', 'next')) {
            $response = $getAllDeputyApiService->listByUrl($next['href']);

            foreach ($response['dados'] as $row) {
                $deputyUpsertService->upsertOne($row);
            }

            $links = $response['links'] ?? [];
        }
    }
}
