<?php

namespace App\Entity;

use App\Enum\WalletEventType;
use App\Repository\WalletEventsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: WalletEventsRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table('wallet_events')]
class WalletEvents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Wallet::class, inversedBy: "wallet_events")]
    private Wallet $wallet;

    #[ORM\Column(type: 'float')]
    private float $amount;

    #[ORM\Column(type: 'string', enumType: WalletEventType::class)]
    private WalletEventType $type;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWallet(): Wallet
    {
        return $this->wallet;
    }

    public function setWallet(Wallet $wallet): void
    {
        $this->wallet = $wallet;
    }

    public function getType(): WalletEventType
    {
        return $this->type;
    }

    public function setType(WalletEventType $type): void
    {
        $this->type = $type;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
}
