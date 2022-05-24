<?php

declare(strict_types=1);

namespace App\Service\Wallet\Show;

use App\Exception\WalletNotFoundException;

interface ShowWalletServiceInterface
{
    /**
     * @throws WalletNotFoundException
     */
    public function show(string $iban): array;
}
