<?php

declare(strict_types=1);

namespace App\Service\History\FileType;

interface WalletHistoryFileInterface
{
    public function generate();
}