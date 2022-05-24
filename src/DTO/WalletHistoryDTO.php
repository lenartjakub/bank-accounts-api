<?php

declare(strict_types=1);

namespace App\DTO;

use App\Dictionary\WalletHistoryFileType;
use Symfony\Component\Validator\Constraints as Assert;

class WalletHistoryDTO
{
    #[Assert\Length(
        min: 1,
        max: 32,
        minMessage: "Iban is required. It should have 1 characters or more.",
        maxMessage: "Personal id number cannot have more than 32 characters."
    )]
    private string $iban;

    #[Assert\Choice(WalletHistoryFileType::ALL)]
    private string $fileType;

    public function __construct(string $iban, string $fileType)
    {
        $this->iban = $iban;
        $this->fileType = strtolower($fileType);
    }
}
