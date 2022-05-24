<?php

declare(strict_types=1);

namespace App\Service\Wallet\Create;

use App\DTO\WalletDTO;

interface CreateWalletServiceInterface
{
    public function handle(WalletDTO $walletDTO): void;
}
