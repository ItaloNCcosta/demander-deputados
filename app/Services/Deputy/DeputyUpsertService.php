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

    public function upsertByExternalId(array $payload): void
    {
        $data = $payload['dados'] ?? [];

        Deputy::updateOrCreate(
            ['external_id' => (int) ($data['id'] ?? 0)],
            [
                // Legislatura e URIs
                'legislature_id'   => $data['ultimoStatus']['idLegislatura'] ?? null,
                'uri'               => $data['uri'] ?? null,
                'party_uri'        => $data['ultimoStatus']['uriPartido'] ?? null,

                // Nomes
                'name'             => $data['ultimoStatus']['nome']              ?? null,
                'civil_name'       => $data['nomeCivil']                        ?? null,
                'electoral_name'   => $data['ultimoStatus']['nomeEleitoral']    ?? null,

                // Partidos e unidades
                'party_acronym'    => $data['ultimoStatus']['siglaPartido']     ?? null,
                'state_code'       => $data['ultimoStatus']['siglaUf']          ?? null,

                // Dados pessoais
                'gender'           => $data['sexo']                             ?? null,
                'cpf'              => $data['cpf']                              ?? null,
                'birth_date'       => $data['dataNascimento']                  ?? null,
                'birth_state'      => $data['ufNascimento']                    ?? null,
                'birth_city'       => $data['municipioNascimento']             ?? null,
                'death_date'       => $data['dataFalecimento']                 ?? null,
                'education_level'  => $data['escolaridade']                    ?? null,

                // Contatos e foto
                'email'            => $data['ultimoStatus']['email']           ?? null,
                'office_email'     => $data['ultimoStatus']['gabinete']['email']   ?? null,
                'photo_url'        => $data['ultimoStatus']['urlFoto']         ?? null,
                'website_url'      => $data['urlWebsite']                      ?? null,
                'social_links'     => $data['redeSocial']                     ?? null,

                // Status atual
                'status_id'        => $data['ultimoStatus']['id']              ?? null,
                'status_uri'       => $data['ultimoStatus']['uri']             ?? null,
                'status_date'      => $data['ultimoStatus']['data']            ?? null,
                'status_situation' => $data['ultimoStatus']['situacao']        ?? null,
                'status_condition' => $data['ultimoStatus']['condicaoEleitoral'] ?? null,
                'status_description' => $data['ultimoStatus']['descricaoStatus'] ?? null,

                // Gabinete
                'office_name'      => $data['ultimoStatus']['gabinete']['nome']    ?? null,
                'office_building'  => $data['ultimoStatus']['gabinete']['predio']  ?? null,
                'office_room'      => $data['ultimoStatus']['gabinete']['sala']    ?? null,
                'office_floor'     => $data['ultimoStatus']['gabinete']['andar']   ?? null,
                'office_phone'     => $data['ultimoStatus']['gabinete']['telefone'] ?? null,

                'total_expenses'   => 0,
            ]
        );
    }
}
