<?php

declare(strict_types=1);

namespace App\Services\Deputy;

use App\Models\Deputy;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

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

    public function list(int $id, array $filters = []): array
    {
        $response = $this->http->retry(3, 100)->get("deputados/{$id}/despesas", $filters);

        return $response->json();
    }
}
