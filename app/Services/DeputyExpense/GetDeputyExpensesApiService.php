<?php

declare(strict_types=1);

namespace App\Services\DeputyExpense;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
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
        $response = Str::startsWith($uri, ['http://', 'https://'])
            ? $this->http->get($uri)
            : $this->http->get($uri, $query);

        return $response
            ->throw()
            ->json();
    }

    public function list(int $id, array $filters = []): array
    {
        return $this->request("deputados/{$id}/despesas", $filters);
    }

    public function listByUrl(string $url): array
    {
        return $this->request($url);
    }
}
