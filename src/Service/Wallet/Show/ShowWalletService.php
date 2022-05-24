<?php

declare(strict_types=1);

namespace App\Service\Wallet\Show;

use App\Exception\WalletNotFoundException;
use App\Repository\Interfaces\WalletRepositoryInterface;
use App\Service\Balance\BalanceGenerator;

class ShowWalletService implements ShowWalletServiceInterface
{
    private WalletRepositoryInterface $walletRepository;

    public function __construct(WalletRepositoryInterface $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    public function show(string $iban): array
    {
        $wallet = $this->walletRepository->findByIban($iban);

        if(is_null($wallet)) {
            throw new WalletNotFoundException('Wallet not found.');
        }

        return [
            'iban' => $iban,
            'balance' => BalanceGenerator::generate($wallet->getWalletEvents()),
            'currency' => $wallet->getCurrency()
        ];
    }
}
