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
        'parliamentary_name',
        'state_code',
        'party_acronym',
        'gender',
        'email',
        'uri',
        'party_uri',
        'photo_url',
        'page',
        'per_page',
        'start_date',
        'end_date',
        'order_direction',
        'order_by',
        'accept_header',
    ];

    protected $casts = [
        'external_id'    => 'integer',
        'legislature_id' => 'integer',
        'page'           => 'integer',
        'per_page'       => 'integer',
        'start_date'     => 'date',
        'end_date'       => 'date',
        'gender'         => GenderEnum::class,
    ];
}
