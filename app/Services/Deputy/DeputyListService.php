<?php

declare(strict_types=1);

namespace App\Services\Deputy;

use App\Models\Deputy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class DeputyListService
{
    public function __construct(protected Deputy $deputy)
    {
        $this->deputy = $deputy;
    }

    public function listByFilters(array $filters = [], int $limit = 20): Collection|LengthAwarePaginator
    {
        return $this->deputy->query()
            ->with(['expenses'])
            ->when($filters['name'] ?? null, function (Builder $query, $name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->when($filters['state'] ?? null, function (Builder $query, $state_code) {
                $query->where('state_code', $state_code);
            })
            ->when($filters['party'] ?? null, function (Builder $query, $party_acronym) {
                $query->where('party_acronym', $party_acronym);
            })
            ->orderBy('name')
            ->paginate($limit)
            ->appends($filters);
    }
}
