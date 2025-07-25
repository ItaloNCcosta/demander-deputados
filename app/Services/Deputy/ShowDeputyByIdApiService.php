<?php

declare(strict_types=1);

namespace App\Services\Deputy;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

final class ShowDeputyByIdApiService
{
    private readonly PendingRequest $http;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->http = Http::acceptJson()
            ->baseUrl(config('services.deputies.url'))
            ->timeout(10);
    }

    public function showById(int $id): array
    {
        $response = $this->http->retry(3, 100)->get("deputados/{$id}");

        return $response->json();
    }
}
