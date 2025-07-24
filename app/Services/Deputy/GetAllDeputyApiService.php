<?php

declare(strict_types=1);

namespace App\Services\Deputy;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

final class GetAllDeputyApiService
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

    public function list(array $filters = []): array
    {
        $response = $this->http->retry(3, 100)->get('deputados', $filters);

        return $response->json();
    }

    public function listByUrl(string $url): array
    {
        $response = $this->http
            ->retry(3, 100)
            ->get($url);

        return $response->json();
    }
}
