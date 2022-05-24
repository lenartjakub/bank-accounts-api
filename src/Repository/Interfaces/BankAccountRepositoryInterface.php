<?php

declare(strict_types=1);

namespace App\Repository\Interfaces;

use App\Entity\BankAccount;

interface BankAccountRepositoryInterface
{
    public function add(BankAccount $entity, bool $flush = false): void;
    public function remove(BankAccount $entity, bool $flush = false): void;
    public function findByPersonalIdNumber(string $personalId): ?BankAccount;
}
