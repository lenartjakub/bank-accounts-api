<?php

declare(strict_types=1);

namespace App\Tests\unit\Service;

use App\Entity\Wallet;
use App\Entity\WalletEvents;
use App\Exception\WalletNotFoundException;
use App\Repository\Interfaces\WalletRepositoryInterface;
use App\Service\Wallet\Show\ShowWalletService;
use DateTime;
use PHPUnit\Framework\TestCase;

class ShowWalletServiceTest extends TestCase
{
    private ShowWalletService $showWalletService;
    private WalletRepositoryInterface $walletRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->walletRepository = $this->createMock(WalletRepositoryInterface::class);
        $this->walletEvents = $this->createMock(WalletEvents::class);
        $this->showWalletService = new ShowWalletService($this->walletRepository);
    }

    /**
     * @throws WalletNotFoundException
     */
    public function testShowWalletWhenExists(): void
    {
        $this->walletRepository->expects($this->once())
            ->method('findByIban')
            ->willReturn($this->exampleWallet());

        $wallet = $this->showWalletService->show('PL33416704205505834192975507');

        self::assertIsArray($wallet);
        self::assertEquals($this->expectedValues(), $wallet);
    }

    /**
     * @throws WalletNotFoundException
     */
    public function testShowWalletWhenDoesntExists(): void
    {
        $this->walletRepository->expects($this->once())
            ->method('findByIban')
            ->willReturn(null);

        $this->expectException(WalletNotFoundException::class);

        $this->showWalletService->show('PL33416704205505834192975507');
    }

    private function exampleWallet(): Wallet
    {
        $date = new DateTime('now');

        $wallet = new Wallet();
        $wallet->setIban('PL33416704205505834192975507');
        $wallet->setCurrency('EUR');
        $wallet->setCreatedAt($date);
        $wallet->setUpdatedAt($date);

        return $wallet;
    }

    private function expectedValues(): array
    {
        return [
            "iban" => "PL33416704205505834192975507",
            "balance" => 0.0,
            "currency" => "EUR",
        ];
    }
}
