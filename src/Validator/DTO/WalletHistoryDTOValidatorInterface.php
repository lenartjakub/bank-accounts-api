<?php

declare(strict_types=1);

namespace App\Validator\DTO;

use App\DTO\WalletHistoryDTO;

interface WalletHistoryDTOValidatorInterface
{
    public function validate(WalletHistoryDTO $walletHistoryDTO): void;
}
