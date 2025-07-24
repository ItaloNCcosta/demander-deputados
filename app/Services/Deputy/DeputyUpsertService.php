<?php

declare(strict_types=1);

namespace App\Services\Deputy;

use App\Models\Deputy;

final class DeputyUpsertService
{
    public function upsertOne(array $row): void
    {
        Deputy::updateOrCreate(
            ['external_id' => (int) $row['id']],
            [
                'legislature_id' => $row['idLegislatura'] ?? null,
                'name' => $row['nome'] ?? null,
                'state_code' => $row['siglaUf'] ?? null,
                'party_acronym' => $row['siglaPartido'] ?? null,
                'email' => $row['email'] ?? null,
                'photo_url' => $row['urlFoto'] ?? null,
                'uri' => $row['uri'] ?? null,
                'party_uri' => $row['uriPartido'] ?? null,
            ]
        );
    }
}
