<?php

declare(strict_types=1);

namespace App\Service\Balance;

use App\Entity\WalletEvents;
use App\Enum\WalletEventType;
use Doctrine\Common\Collections\Collection;

class BalanceCalculator
{
    public static function calculate(Collection $events): float
    {
        $balance = 0;

        /** @var WalletEvents $event*/
        foreach ($events as $event) {
            if ($event->getType() === WalletEventType::DEPOSIT) {
                $balance +=$event->getAmount();
            }

            if ($event->getType() === WalletEventType::WITHDRAW) {
                $balance -=$event->getAmount();
            }
        }

        return round($balance, 2);
    }
}
