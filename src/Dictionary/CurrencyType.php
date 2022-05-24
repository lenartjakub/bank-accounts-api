<?php

declare(strict_types=1);

namespace App\Dictionary;

class CurrencyType
{
    public const EUR = 'EUR';
    public const USD = 'USD';
    public const PLN = 'PLN';

    public const ALL = [
        self::EUR,
        self::USD,
        self::PLN
    ];
}
