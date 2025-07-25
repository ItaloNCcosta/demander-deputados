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
                'page' => $row['page'] ?? null,
                'per_page' => $row['per_page'] ?? null,
                'start_date' => $row['start_date'] ?? null,
                'end_date' => $row['end_date'] ?? null,
                'order_direction' => $row['order_direction'] ?? null,
                'order_by' => $row['order_by'] ?? null,
                'accept_header' => $row['accept_header'] ?? null,

                'name' => $row['nome'] ?? null,
                'civil_name' => $row['nomeCivil'] ?? null,
                'electoral_name' => $row['ultimoStatus']['nomeEleitoral'] ?? null,
                'state_code' => $row['siglaUf'] ?? null,
                'party_acronym' => $row['siglaPartido'] ?? null,
                'gender' => $row['sexo'] ?? null,
                'cpf' => $row['cpf'] ?? null,
                'birth_date' => $row['dataNascimento'] ?? null,
                'birth_state' => $row['ufNascimento'] ?? null,
                'birth_city' => $row['municipioNascimento'] ?? null,
                'death_date' => $row['dataFalecimento'] ?? null,
                'education_level' => $row['escolaridade'] ?? null,

                'email' => $row['ultimoStatus']['email'] ?? null,
                'website_url' => $row['urlWebsite'] ?? null,
                'social_links' => $row['redeSocial'] ?? null,
                'uri' => $row['uri'] ?? null,
                'party_uri' => $row['uriPartido'] ?? null,
                'photo_url' => $row['ultimoStatus']['urlFoto'] ?? null,

                'status_id' => $row['ultimoStatus']['id'] ?? null,
                'status_uri' => $row['ultimoStatus']['uri'] ?? null,
                'status_date' => $row['ultimoStatus']['data'] ?? null,
                'status_situation' => $row['ultimoStatus']['situacao'] ?? null,
                'status_condition' => $row['ultimoStatus']['condicaoEleitoral'] ?? null,
                'status_description' => $row['ultimoStatus']['descricaoStatus'] ?? null,

                'office_name' => $row['ultimoStatus']['gabinete']['nome'] ?? null,
                'office_building' => $row['ultimoStatus']['gabinete']['predio'] ?? null,
                'office_room' => $row['ultimoStatus']['gabinete']['sala'] ?? null,
                'office_floor' => $row['ultimoStatus']['gabinete']['andar'] ?? null,
                'office_phone' => $row['ultimoStatus']['gabinete']['telefone'] ?? null,
                'office_email' => $row['ultimoStatus']['gabinete']['email'] ?? null,

                'total_expenses' => $row['total_expenses'] ?? 0,
            ]
        );
    }
}
