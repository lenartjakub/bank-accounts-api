<?php

declare(strict_types=1);

namespace App\Service\Wallet;

use App\DTO\WalletOperationDTOInterface;
use App\Entity\Wallet;
use App\Entity\WalletEvents;
use App\Enum\WalletEventType;
use App\Exception\WalletNotFoundException;
use App\Repository\Interfaces\WalletEventsRepositoryInterface;
use App\Repository\Interfaces\WalletRepositoryInterface;
use App\Validator\DTO\WalletOperationDTOValidatorInterface;

abstract class AbstractWalletOperationService
{
    protected WalletOperationDTOValidatorInterface $operationDTOValidator;
    protected WalletRepositoryInterface $walletRepository;
    protected WalletEventsRepositoryInterface $walletEventsRepository;

    public function __construct(
        WalletOperationDTOValidatorInterface $operationDTOValidator,
        WalletRepositoryInterface $walletRepository,
        WalletEventsRepositoryInterface $walletEventsRepository
    )
    {
        $this->operationDTOValidator = $operationDTOValidator;
        $this->walletRepository = $walletRepository;
        $this->walletEventsRepository = $walletEventsRepository;
    }

    /**
     * @throws WalletNotFoundException
     */
    abstract public function handle(WalletOperationDTOInterface $walletOperationDTO): void;

    protected function validate(WalletOperationDTOInterface $walletOperationDTO): void
    {
        $this->operationDTOValidator->validate($walletOperationDTO);
    }

    protected function getWalletByIban(string $iban): ?Wallet
    {
        return $this->walletRepository->findByIban($iban);
    }

    protected function createWalletEvent(
        Wallet $wallet,
        WalletOperationDTOInterface $walletOperationDTO,
        WalletEventType $eventType
    ): WalletEvents
    {
        $walletEvent = new WalletEvents();
        $walletEvent->setWallet($wallet);
        $walletEvent->setType($eventType);
        $walletEvent->setAmount($walletOperationDTO->getAmount());

        return $walletEvent;
    }

    protected function addEvent(
        Wallet $wallet,
        WalletOperationDTOInterface $walletOperationDTO,
        WalletEventType $type
    ): void
    {
        $this->walletEventsRepository->add(
            $this->createWalletEvent($wallet, $walletOperationDTO, $type),
            true
        );
    }

    /**
     * @throws WalletNotFoundException
     */
    protected function isWalletExists(?Wallet $wallet): void
    {
        if (is_null($wallet)) {
            throw new WalletNotFoundException('Wallet not found.');
        }
    }
}
