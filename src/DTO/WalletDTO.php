<?php

declare(strict_types=1);

namespace App\DTO;

use App\Dictionary\CurrencyType;
use Symfony\Component\Validator\Constraints as Assert;

class WalletDTO
{
    #[Assert\Length(
        min: 1,
        max: 32,
        minMessage: "Personal id number is required. It should have 1 characters or more.",
        maxMessage: "Personal id number cannot have more than 32 characters."
    )]
    private string $personalIdNumber;

    #[Assert\Choice(CurrencyType::ALL)]
    #[Assert\NotNull(message: "Currency  is required.")]
    private string $currency;

    public function __construct(string $personalIdNumber, string $currency)
    {
        $this->personalIdNumber = $personalIdNumber;
        $this->currency = $currency;
    }

    public function getPersonalIdNumber(): string
    {
        return $this->personalIdNumber;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
