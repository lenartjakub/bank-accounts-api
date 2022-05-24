<?php

declare(strict_types=1);

namespace App\Service\History;

use App\DTO\WalletHistoryDTO;
use App\Enum\WalletEventType;
use App\Exception\NoSufficientFundsException;
use App\Exception\WalletNotFoundException;
use App\Repository\Interfaces\WalletRepositoryInterface;
use App\Validator\DTO\WalletHistoryDTOValidatorInterface;
use Doctrine\Common\Collections\Collection;

class WalletHistoryService implements WalletHistoryServiceInterface
{
    private WalletHistoryDTOValidatorInterface $historyDTOValidator;
    private WalletRepositoryInterface $walletRepository;
    private WalletHistoryFactory $historyFactory;

    public function __construct(
        WalletHistoryDTOValidatorInterface $historyDTOValidator,
        WalletRepositoryInterface $walletRepository,
        WalletHistoryFactory $historyFactory
    )
    {
        $this->historyDTOValidator = $historyDTOValidator;
        $this->walletRepository = $walletRepository;
        $this->historyFactory = $historyFactory;
    }

    public function handle(string $iban, string $fileType): string
    {
        $walletHistoryDTO = new WalletHistoryDTO($iban, $fileType);
        $this->historyDTOValidator->validate($walletHistoryDTO);

        $wallet = $this->walletRepository->findByIban($iban);

        if (is_null($wallet)) {
            throw new WalletNotFoundException('Wallet not found.');
        }

        $walletEvents = $wallet->getWalletEvents();

        if ($this->hasOnlyInitialEvent($walletEvents)) {
            throw new NoSufficientFundsException("History doesn't exists. Probably your wallet is empty.");
        }

        $fileGenerator = $this->historyFactory->make($fileType, $walletEvents);

        return $fileGenerator->generate();
    }

    private function hasOnlyInitialEvent(Collection $events): bool
    {
        return $events->count() === 1 && $events->first()->getType() === WalletEventType::Initial;
    }
}