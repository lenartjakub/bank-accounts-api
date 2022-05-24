<?php

declare(strict_types=1);

namespace App\Repository\Interfaces;

use App\Entity\Wallet;

interface WalletRepositoryInterface
{
    public function add(Wallet $entity, bool $flush = false): void;
    public function remove(Wallet $entity, bool $flush = false): void;
    public function findByIban(string $iban): ?Wallet;
}
