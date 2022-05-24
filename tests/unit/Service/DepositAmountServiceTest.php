<?php

namespace App\Tests\unit\Service;

use App\DTO\DepositDTO;
use App\Entity\Wallet;
use App\Exception\WalletNotFoundException;
use App\Repository\Interfaces\WalletEventsRepositoryInterface;
use App\Repository\Interfaces\WalletRepositoryInterface;
use App\Service\Wallet\Deposit\DepositAmountService;
use App\Validator\DTO\WalletOperationDTOValidatorInterface;
use DateTime;
use PHPUnit\Framework\TestCase;
use Generator;

class DepositAmountServiceTest extends TestCase
{
    private WalletRepositoryInterface $walletRepository;
    private DepositAmountService $depositAmountService;

    public function setUp(): void
    {
        parent::setUp();

        $this->walletRepository = $this->createMock(WalletRepositoryInterface::class);
        $operationDTOValidator = $this->createMock(WalletOperationDTOValidatorInterface::class);
        $walletEventsRepository = $this->createMock(WalletEventsRepositoryInterface::class);

        $this->depositAmountService = new DepositAmountService(
            $operationDTOValidator,
            $this->walletRepository,
            $walletEventsRepository
        );
    }

    /**
     * @dataProvider validDepositDTO
     */
    public function testDepositAmountSuccess(DepositDTO $depositDTO): void
    {
        $this->walletRepository->expects($this->once())
            ->method('findByIban')
            ->willReturn($this->exampleWallet());

        $this->depositAmountService->handle($depositDTO);
    }

    /**
     * @dataProvider invalidDepositDTO
     */
    public function testDepositAmountWhenWalletDoesntExists(DepositDTO $depositDTO): void
    {
        $this->walletRepository->expects($this->once())
            ->method('findByIban')
            ->willReturn(null);

        $this->expectException(WalletNotFoundException::class);
        $this->expectExceptionMessage('Wallet not found');

        $this->depositAmountService->handle($depositDTO);
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

    private function invalidDepositDTO(): Generator
    {
        yield [new DepositDTO('', 9)];
        yield [new DepositDTO('1231231231231231232131232312321312323', 10001)];
    }

    private function validDepositDTO(): Generator
    {
        yield [new DepositDTO('123123', 123)];
        yield [new DepositDTO('123123123', 5000.34)];
        yield [new DepositDTO('1231231231231231232131232312321312323', 10)];
    }
}
