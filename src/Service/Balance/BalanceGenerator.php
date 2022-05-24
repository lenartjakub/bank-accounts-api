<?php

declare(strict_types=1);

namespace App\Service\Balance;

use App\Entity\WalletEvents;
use App\Enum\WalletEventType;
use Doctrine\Common\Collections\Collection;

class BalanceGenerator
{
    public static function generate(Collection $events): float
    {
        $balance = 0;

        /** @var WalletEvents $event*/
        foreach ($events as $event) {
            if ($event->getType() === WalletEventType::Deposit) {
                $balance +=$event->getAmount();
            }

            if ($event->getType() === WalletEventType::Withdraw) {
                $balance -=$event->getAmount();
            }
        }

        return round($balance, 2);
    }
}
