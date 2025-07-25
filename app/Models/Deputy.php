<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GenderEnum;
use Illuminate\Database\Eloquent\Model;

final class Deputy extends Model
{
    protected $fillable = [
        'external_id',
        'legislature_id',
        'page',
        'per_page',

        // básicos
        'name',
        'civil_name',
        'electoral_name',
        'state_code',
        'party_acronym',
        'gender',
        'cpf',
        'birth_date',
        'birth_state',
        'birth_city',
        'death_date',
        'education_level',

        // contatos e links
        'email',
        'website_url',
        'social_links',
        'uri',
        'party_uri',
        'photo_url',

        // filtros e ordenação
        'start_date',
        'end_date',
        'order_direction',
        'order_by',
        'accept_header',

        // último status
        'status_id',
        'status_uri',
        'status_date',
        'status_situation',
        'status_condition',
        'status_description',

        // gabinete
        'office_name',
        'office_building',
        'office_room',
        'office_floor',
        'office_phone',
        'office_email',

        'total_expenses',
    ];

    protected $casts = [
        'external_id' => 'integer',
        'legislature_id' => 'integer',
        'page' => 'integer',
        'per_page' => 'integer',

        // datas
        'start_date' => 'date',
        'end_date' => 'date',
        'birth_date' => 'date',
        'death_date' => 'date',
        'status_date' => 'date',

        // enum
        'gender' => GenderEnum::class,

        // JSON
        'social_links' => 'array',

        // status_id
        'status_id' => 'integer',
        'total_expenses' => 'decimal:4',
    ];

    public function expenses()
    {
        return $this->hasMany(DeputyExpense::class);
    }
}
