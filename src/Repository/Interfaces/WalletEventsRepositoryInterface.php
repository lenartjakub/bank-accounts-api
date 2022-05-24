<?php

declare(strict_types=1);

namespace App\Repository\Interfaces;

use App\Entity\WalletEvents;

interface WalletEventsRepositoryInterface
{
    public function add(WalletEvents $entity, bool $flush = false): void;
}
