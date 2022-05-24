<?php

namespace App\Service\History\FileType;

use App\Entity\WalletEvents;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class WalletHistoryCsv implements WalletHistoryFileInterface
{
    private Collection $events;
    private EncoderInterface $serializer;

    public function __construct(Collection $events, EncoderInterface $serializer)
    {
        $this->events = $events;
        $this->serializer = $serializer;
    }

    public function generate(): string
    {
        $data = [];

        /** @var WalletEvents $event*/

        foreach ($this->events as $event) {
            array_unshift($data, [
                'operation' => $event->getType()->name,
                'amount' => $event->getAmount(),
                'date' => $event->getCreatedAt()->format("Y-m-d H:i:s")
            ]);
        }

        return $this->serializer->encode($data, CsvEncoder::FORMAT);
    }
}
