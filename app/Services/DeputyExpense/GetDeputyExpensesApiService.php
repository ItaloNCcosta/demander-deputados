<?php

declare(strict_types=1);

namespace App\Services\DeputyExpense;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class GetDeputyExpensesApiService
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
                ? $this->http->get($uri)
                : $this->http->get($uri, $query);

            $response->throw();

            return $response->json();
        } catch (RequestException $e) {
            Log::warning("Erro ao buscar {$uri}", [
                'status' => $e->response?->status(),
                'query'  => $query,
            ]);

            return [
                'dados' => [],
                'links' => []
            ];
        }
    }

    public function list(int $id, array $filters = []): array
    {
        $key = "deputy_expenses_{$id}_" . md5(json_encode($filters));

        return Cache::remember(
            $key,
            now()->addMinutes(1),
            fn() =>
            $this->request("deputados/{$id}/despesas", $filters)
        );
    }

    public function listByUrl(string $url): array
    {
        $key = 'deputy_expenses_by_url_' . md5($url);

        return Cache::remember(
            $key,
            now()->addMinutes(1),
            fn() => $this->request($url)
        );
    }
}
