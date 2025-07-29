<?php

declare(strict_types=1);

namespace App\Services\Deputy;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    private function request(string $uri): array
    {
        try {
            $response = $this->http
                ->retry(3, 100)
                ->get($uri);

            $response->throw();

            return $response->json();
        } catch (RequestException $e) {
            Log::warning("Failed to fetch deputy {$uri}", [
                'status' => $e->response?->status(),
                'uri'    => $uri,
            ]);

            return ['dados' => []];
        }
    }

    public function showById(int $id): array
    {
        return $this->request("deputados/{$id}");
    }
}
