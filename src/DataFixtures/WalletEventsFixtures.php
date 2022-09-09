<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\WalletEvents;
use App\Enum\WalletEventType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WalletEventsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++) {
            $event = new WalletEvents();
            $event->setWallet($this->getReference(WalletsFixtures::WALLET_REFERENCE));
            $event->setType(WalletEventType::DEPOSIT);
            $event->setAmount(10000);

            $manager->persist($event);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            WalletsFixtures::class
        ];
    }
}
