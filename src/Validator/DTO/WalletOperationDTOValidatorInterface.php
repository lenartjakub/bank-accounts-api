<?php

declare(strict_types=1);

namespace App\Validator\DTO;

use App\DTO\WalletOperationDTO;

interface WalletOperationDTOValidatorInterface
{
    public function validate(WalletOperationDTO $walletDTO): void;
}
