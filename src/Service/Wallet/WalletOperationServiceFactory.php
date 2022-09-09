<?php

declare(strict_types=1);

namespace App\Service\Wallet;

use App\Enum\WalletEventType;
use App\Repository\Interfaces\WalletEventsRepositoryInterface;
use App\Repository\Interfaces\WalletRepositoryInterface;
use App\Service\Wallet\Deposit\DepositAmountService;
use App\Service\Wallet\Withdraw\WithdrawAmountService;
use App\Validator\DTO\WalletOperationDTOValidatorInterface;
use Exception;

class WalletOperationServiceFactory
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
     * @throws Exception
     */
    public function make(WalletEventType $eventType): AbstractWalletOperationService
    {
        return match ($eventType) {
            WalletEventType::DEPOSIT => new DepositAmountService(
                $this->operationDTOValidator,
                $this->walletRepository,
                $this->walletEventsRepository
            ),
            WalletEventType::WITHDRAW => new WithdrawAmountService(
                $this->operationDTOValidator,
                $this->walletRepository,
                $this->walletEventsRepository
            ),
            default => throw new Exception('Unexpected match value'),
        };
    }
}
