<?php

declare(strict_types=1);

namespace App\Service\History;

use App\Exception\NoFundsException;
use App\Exception\UnsupportedFileTypeException;
use App\Exception\WalletNotFoundException;

interface WalletHistoryServiceInterface
{
    /**
     * @throws WalletNotFoundException
     * @throws NoFundsException
     * @throws UnsupportedFileTypeException
     */
    public function handle(string $iban, string $fileType): string;
}
