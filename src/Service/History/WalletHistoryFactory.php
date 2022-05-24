<?php

declare(strict_types=1);

namespace App\Service\History;

use App\Dictionary\WalletHistoryFileType;
use Doctrine\Common\Collections\Collection;
use App\Exception\UnsupportedFileTypeException;
use App\Service\History\FileType\WalletHistoryCsv;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class WalletHistoryFactory
{
    private EncoderInterface $serializer;

    public function __construct(EncoderInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    /**
     * @throws UnsupportedFileTypeException
     */
    public function make(string $fileType, Collection $events): WalletHistoryCsv
    {
        return match ($fileType) {
            WalletHistoryFileType::CSV => new WalletHistoryCsv($events, $this->serializer),
            default => throw new UnsupportedFileTypeException('We do not support this file format')
        };
    }
}
