<?php

declare(strict_types=1);

namespace App\Enum;

enum WalletEventType: string
{
    case Withdraw = 'WITHDRAW';
    case Deposit = 'DEPOSIT';
    case Initial = 'INITIAL';
}
