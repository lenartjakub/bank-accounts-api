<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BankAccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: BankAccountRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table('bank_accounts')]
class BankAccount
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 64, unique: false)]
    private string $name;

    #[ORM\Column(type: 'string', length: 64, unique: false)]
    private string $surname;

    #[ORM\Column(type: 'string', length: 11, unique: true)]
    private string $personalIdNumber;

    #[ORM\OneToMany(mappedBy: "bank_account", targetEntity: Wallet::class)]
    private Collection $wallets;

    public function __construct()
    {
        $this->wallets = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getPersonalIdNumber(): string
    {
        return $this->personalIdNumber;
    }

    public function setPersonalIdNumber(string $personalIdNumber): void
    {
        $this->personalIdNumber = $personalIdNumber;
    }

    public function getWallets(): ArrayCollection
    {
        return $this->wallets;
    }
}
