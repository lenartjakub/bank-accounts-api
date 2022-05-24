<?php

declare(strict_types=1);

namespace App\Service\Wallet\Deposit;

use App\DTO\WalletOperationDTOInterface;
use App\Enum\WalletEventType;
use App\Service\Wallet\AbstractWalletOperationService;

class DepositAmountService extends AbstractWalletOperationService
{
    public function handle(WalletOperationDTOInterface $walletOperationDTO): void
    {
        $this->validate($walletOperationDTO);
        $wallet = $this->getWalletByIban($walletOperationDTO->getIban());

        $this->isWalletExists($wallet);

        $this->addEvent($wallet, $walletOperationDTO, WalletEventType::Deposit);
    }
}
