<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class WalletOperationDTO implements WalletOperationDTOInterface
{
    #[Assert\Length(
        min: 1,
        max: 32,
        minMessage: "Iban is required. It should have 1 characters or more.",
        maxMessage: "Personal id number cannot have more than 32 characters."
    )]
    private string $iban;

    #[Assert\Range(
        notInRangeMessage: 'Amount must be between 10 and 10 000.',
        min: 10,
        max: 10000,
    )]
    private float $amount;

    public function __construct(string $iban, float $amount)
    {
        $this->iban = $iban;
        $this->amount = $amount;
    }

    public function getIban(): string
    {
        return $this->iban;
    }

    public function setIban(string $iban): void
    {
        $this->iban = $iban;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
}
