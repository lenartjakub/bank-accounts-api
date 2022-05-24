<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table('wallets')]
class Wallet
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\OneToMany(mappedBy: "wallet", targetEntity: WalletEvents::class, cascade: ['persist', 'remove'])]
    private Collection $walletEvents;

    #[ORM\ManyToOne(targetEntity: BankAccount::class, inversedBy: "wallets")]
    private BankAccount $bankAccount;

    #[ORM\Column(type: 'string', length: 32, unique: true)]
    private string $iban;

    #[ORM\Column(type: 'string', length: 255)]
    private string $currency;

    public function __construct()
    {
        $this->walletEvents = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getBankAccount(): BankAccount
    {
        return $this->bankAccount;
    }

    public function setBankAccount(BankAccount $bankAccount): void
    {
        $this->bankAccount = $bankAccount;
    }


    public function getIban(): string
    {
        return $this->iban;
    }

    public function setIban(string $iban): void
    {
        $this->iban = $iban;
    }

    public function getWalletEvents(): Collection
    {
        return $this->walletEvents;
    }
}
