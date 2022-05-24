<?php

declare(strict_types=1);

namespace App\DTO;

interface WalletOperationDTOInterface
{
    public function getIban(): string;
    public function setIban(string $iban): void;
    public function getAmount(): float;
    public function setAmount(float $amount): void;
}
