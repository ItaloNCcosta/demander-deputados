<?php

declare(strict_types=1);

namespace App\Services\Deputy;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

    private function request(string $uri, array $query = []): array
    {
        try {
            $response = Str::startsWith($uri, ['http://', 'https://'])
                ? $this->http->retry(3, 100)->get($uri)
                : $this->http->retry(3, 100)->get($uri, $query);

            $response->throw();

            return $response->json();
        } catch (RequestException $e) {
            Log::warning("Erro ao buscar {$uri}", [
                'status' => $e->response?->status(),
                'query'  => $query,
            ]);

            return [
                'dados' => [],
                'links' => [],
            ];
        }
    }

    public function list(array $filters = []): array
    {
        $key = 'all_deputies_' . md5(json_encode($filters));

        return Cache::remember(
            $key,
            now()->addMinutes(1),
            fn() =>
            $this->request('deputados', $filters)
        );
    }

    public function listByUrl(string $url): array
    {
        $key = 'all_deputies_url_' . md5($url);

        return Cache::remember(
            $key,
            now()->addMinutes(1),
            fn() => $this->request($url)
        );
    }
}
