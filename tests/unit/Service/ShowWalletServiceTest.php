<?php

declare(strict_types=1);

namespace App\Tests\unit\Service;

use App\Exception\WalletNotFoundException;
use App\Repository\Interfaces\WalletRepositoryInterface;
use App\Service\Wallet\Show\ShowWalletService;
use PHPUnit\Framework\TestCase;

class ShowWalletServiceTest extends TestCase
{
    private ShowWalletService $showWalletService;
    private WalletRepositoryInterface $walletRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->walletRepository = $this->createMock(WalletRepositoryInterface::class);

        $this->showWalletService = new ShowWalletService($this->walletRepository);
    }

    /**
     * @throws WalletNotFoundException
     */
    public function testShowWalletWhenExists(): void
    {
        $this->walletRepository->expects($this->once())
            ->method('findByIban')
            ->willReturn([
                "iban" => "PL33416704205505834192975507",
                "balance" => 67868757,
                "currency" => "EUR"
            ]);

        $wallet = $this->showWalletService->show('PL33416704205505834192975507');

        self::assertNotNull($wallet);
        self::assertIsArray($wallet);
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
}