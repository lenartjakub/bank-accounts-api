<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\BankAccount;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Faker\Provider\pl_PL\Person;

class BankAccountFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $account = new BankAccount();
            $account->setName($faker->firstName);
            $account->setSurname($faker->lastName);
            $account->setPersonalIdNumber(Person::pesel());
            $manager->persist($account);
        }

        $manager->flush();
    }
}
