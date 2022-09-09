<?php

declare(strict_types=1);

namespace App\Enum;

enum WalletEventType: string
{
    case WITHDRAW = 'WITHDRAW';
    case DEPOSIT = 'DEPOSIT';
    case INITIAL = 'INITIAL';
}
