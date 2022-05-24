<?php

declare(strict_types=1);

namespace App\Tests\unit\Service;

use App\DTO\WalletDTO;
use App\Entity\BankAccount;
use App\Exception\BankAccountNotFoundException;
use App\Repository\Interfaces\BankAccountRepositoryInterface;
use App\Repository\Interfaces\WalletEventsRepositoryInterface;
use App\Repository\Interfaces\WalletRepositoryInterface;
use App\Service\Wallet\Create\CreateWalletService;
use App\Service\Wallet\Create\CreateWalletServiceInterface;
use App\Validator\DTO\WalletDTOValidatorInterface;
use PHPUnit\Framework\TestCase;
use Generator;

class CreateWalletServiceTest extends TestCase
{
    private CreateWalletServiceInterface $createWalletService;
    private BankAccountRepositoryInterface $bankAccountRepository;

    public function setUp(): void
    {
        parent::setUp();

        $walletRepository = $this->createMock(WalletRepositoryInterface::class);
        $this->bankAccountRepository = $this->createMock(BankAccountRepositoryInterface::class);
        $walletEventsRepository = $this->createMock(WalletEventsRepositoryInterface::class);
        $walletDTOValidator = $this->createMock(WalletDTOValidatorInterface::class);
        $this->createWalletService = new CreateWalletService(
            $this->bankAccountRepository,
            $walletRepository,
            $walletDTOValidator,
            $walletEventsRepository
        );
    }

    public function testCreateWalletSuccess(): void
    {
        $this->bankAccountRepository->expects($this->once())
            ->method('findByPersonalIdNumber')
            ->willReturn($this->exampleBankAccount());

        $this->createWalletService->handle($this->getExampleWalletDTO());
    }

    /**
     * @dataProvider invalidWalletDTO
     */
    public function testExceptionWhenBankAccountDoesntExists(WalletDTO $walletDTO)
    {
        $this->bankAccountRepository->expects($this->once())
            ->method('findByPersonalIdNumber')
            ->willReturn(null);

        $this->expectException(BankAccountNotFoundException::class);
        $this->expectExceptionMessage('Account not found.');

        $this->createWalletService->handle($walletDTO);
    }

    private function exampleBankAccount(): BankAccount
    {
        $account = new BankAccount();
        $account->setName('Jan');
        $account->setSurname('Kowalski');
        $account->setPersonalIdNumber('123123123');

        return $account;
    }

    private function getExampleWalletDTO(): WalletDTO
    {
        return new WalletDTO('123123123', 'PLN');
    }

    private function invalidWalletDTO(): Generator
    {
        yield [new WalletDTO('', 'PLN')];
        yield [new WalletDTO('123123123', 'LOL')];
        yield [new WalletDTO('1231231231231231232131232312321312323', 'LOL')];
        yield [new WalletDTO('1231231231231231232131232312321312323', 'EUR')];
    }
}
