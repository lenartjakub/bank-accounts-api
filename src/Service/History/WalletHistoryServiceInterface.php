<?php

declare(strict_types=1);

namespace App\Service\History;

use App\Exception\NoSufficientFundsException;
use App\Exception\UnsupportedFileTypeException;
use App\Exception\WalletNotFoundException;

interface WalletHistoryServiceInterface
{
    /**
     * @throws WalletNotFoundException
     * @throws NoSufficientFundsException
     * @throws UnsupportedFileTypeException
     */
    public function handle(string $iban, string $fileType): string;
}
