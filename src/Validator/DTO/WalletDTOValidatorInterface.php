<?php

declare(strict_types=1);

namespace App\Validator\DTO;

use App\DTO\WalletDTO;

interface WalletDTOValidatorInterface
{
    public function validate(WalletDTO $walletDTO): void;
}
