<?php

declare(strict_types=1);

namespace App\Enums;

enum GenderEnum: string
{
    case MALE = "M";
    case FEMALE = 'F';

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Masculino',
            self::FEMALE => 'Feminino',
        };
    }

    public static function getValues(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}
