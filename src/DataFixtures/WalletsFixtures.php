<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\BankAccount;
use App\Entity\Wallet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WalletsFixtures extends Fixture
{
    public const WALLET_REFERENCE = 'wallet-event';
    private const EXAMPLE_PERSONAL_ID_NUMBER = '00000';
    private const EXAMPLE_CURRENCY_CODE = 'EUR';

    public function load(ObjectManager $manager): void
    {
        $bankAccount = $this->createBankAccount();

        for ($i = 0; $i < 3; $i++) {
            $wallet = new Wallet();
            $wallet->setBankAccount($bankAccount);
            $wallet->setIban('000' . $i);
            $wallet->setCurrency(self::EXAMPLE_CURRENCY_CODE);

            $manager->persist($wallet);
        }

        $manager->persist($bankAccount);
        $manager->flush();

        $this->addReference(self::WALLET_REFERENCE, $wallet);
    }

    private function createBankAccount(): BankAccount
    {
        $account = new BankAccount();
        $account->setName('Admin');
        $account->setSurname('Admin');
        $account->setPersonalIdNumber(self::EXAMPLE_PERSONAL_ID_NUMBER);

        return $account;
    }
}
