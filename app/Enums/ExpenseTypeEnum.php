<?php

declare(strict_types=1);

namespace App\Enums;

enum ExpenseTypeEnum: string
{
    case ALIMENTACAO = 'alimentacao';
    case PASSAGENS   = 'passagens';

    public function label(): string
    {
        return match ($this) {
            self::ALIMENTACAO => 'Alimentação',
            self::PASSAGENS   => 'Passagens',
        };
    }
}
