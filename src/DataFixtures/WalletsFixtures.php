<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\BankAccount;
use App\Entity\Wallet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider\pl_PL\Payment;

class WalletsFixtures extends Fixture
{
    private const EXAMPLE_PERSONAL_ID_NUMBER = '12345678910';
    private const EXAMPLE_CURRENCY_CODE = 'EUR';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $bankAccount = $this->createBankAccount($faker);

        for ($i = 0; $i < 3; $i++) {
            $wallet = new Wallet();
            $wallet->setBankAccount($bankAccount);
            $wallet->setIban(Payment::bankAccountNumber());
            $wallet->setCurrency(self::EXAMPLE_CURRENCY_CODE);

            $manager->persist($wallet);
        }

        $manager->persist($bankAccount);
        $manager->flush();
    }

    private function createBankAccount(Generator $faker): BankAccount
    {
        $account = new BankAccount();
        $account->setName($faker->firstName);
        $account->setSurname($faker->lastName);
        $account->setPersonalIdNumber(self::EXAMPLE_PERSONAL_ID_NUMBER);

        return $account;
    }
}
