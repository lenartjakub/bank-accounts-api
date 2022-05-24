<?php

declare(strict_types=1);

namespace App\Service\Wallet\Create;

use App\DTO\WalletDTO;
use App\Entity\BankAccount;
use App\Entity\Wallet;
use App\Entity\WalletEvents;
use App\Enum\WalletEventType;
use App\Exception\BankAccountNotFoundException;
use App\Repository\Interfaces\BankAccountRepositoryInterface;
use App\Repository\Interfaces\WalletEventsRepositoryInterface;
use App\Repository\Interfaces\WalletRepositoryInterface;
use App\Validator\DTO\WalletDTOValidatorInterface;
use Faker\Provider\pl_PL\Payment;

class CreateWalletService implements CreateWalletServiceInterface
{
    private BankAccountRepositoryInterface $accountRepository;
    private WalletRepositoryInterface $walletRepository;
    private WalletDTOValidatorInterface $walletDTOValidator;
    private WalletEventsRepositoryInterface $walletEventsRepository;

    public function __construct(
        BankAccountRepositoryInterface $accountRepository,
        WalletRepositoryInterface $walletRepository,
        WalletDTOValidatorInterface $walletDTOValidator,
        WalletEventsRepositoryInterface $walletEventsRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->walletRepository = $walletRepository;
        $this->walletDTOValidator = $walletDTOValidator;
        $this->walletEventsRepository = $walletEventsRepository;
    }

    /**
     * @throws BankAccountNotFoundException
     */
    public function handle(WalletDTO $walletDTO): void
    {
        $this->walletDTOValidator->validate($walletDTO);

        $bankAccount = $this->accountRepository->findByPersonalIdNumber($walletDTO->getPersonalIdNumber());

        if (is_null($bankAccount)) {
            throw new BankAccountNotFoundException('Account not found.');
        }

        $wallet = $this->createWallet($bankAccount, $walletDTO);
        $walletEvent = $this->initialWalletEvent($wallet);

        $this->walletRepository->add($wallet, true);
        $this->walletEventsRepository->add($walletEvent, true);
    }

    private function createWallet(BankAccount $bankAccount, WalletDTO $walletDTO): Wallet
    {
        $wallet = new Wallet();
        $wallet->setCurrency($walletDTO->getCurrency());
        $wallet->setIban(Payment::bankAccountNumber());
        $wallet->setCurrency($walletDTO->getCurrency());
        $wallet->setBankAccount($bankAccount);

        return $wallet;
    }

    private function initialWalletEvent(Wallet $wallet): WalletEvents
    {
        $walletEvent = new WalletEvents();
        $walletEvent->setAmount(0);
        $walletEvent->setType(WalletEventType::Initial);
        $walletEvent->setWallet($wallet);

        return $walletEvent;
    }
}
