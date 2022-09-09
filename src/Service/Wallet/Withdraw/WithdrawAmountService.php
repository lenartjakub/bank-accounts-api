<?php

declare(strict_types=1);

namespace App\Service\Wallet\Withdraw;

use App\DTO\WalletOperationDTOInterface;
use App\Enum\WalletEventType;
use App\Exception\NoSufficientFundsException;
use App\Exception\WalletNotFoundException;
use App\Service\Balance\BalanceCalculator;
use App\Service\Wallet\AbstractWalletOperationService;

class WithdrawAmountService extends AbstractWalletOperationService
{
    /**
     * @throws NoSufficientFundsException
     * @throws WalletNotFoundException
     */
    public function handle(WalletOperationDTOInterface $walletOperationDTO): void
    {
        $this->validate($walletOperationDTO);

        $wallet = $this->getWalletByIban($walletOperationDTO->getIban());
        $this->isWalletExists($wallet);

        $walletEvents = $wallet->getWalletEvents();
        $balance = BalanceCalculator::calculate($walletEvents);

        if ($this->isWithdrawGreaterThanBalance($walletOperationDTO, $balance)) {
            throw new NoSufficientFundsException("You do not have sufficient funds in your wallet.");
        }

        $this->addEvent($wallet, $walletOperationDTO, WalletEventType::WITHDRAW);
    }

    private function isWithdrawGreaterThanBalance(WalletOperationDTOInterface $walletOperationDTO, float $balance): bool
    {
        return $walletOperationDTO->getAmount() > $balance;
    }
}
