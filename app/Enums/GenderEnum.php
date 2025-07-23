<?php

declare(strict_types=1);

namespace App\Enums;

enum GenderEnum: string
{
    case MALE = "male";
    case FEMALE = 'female';
    case OTHER = 'other';
    case NOT_INFORMED = 'not_informed';

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Masculino',
            self::FEMALE => 'Feminino',
            self::OTHER => 'Outro',
            self::NOT_INFORMED => 'Prefiro não informar',
        };
    }

    public static function getValues(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}
