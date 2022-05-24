<?php

declare(strict_types=1);

namespace App\Validator\DTO;

use App\DTO\WalletHistoryDTO;
use App\Exception\BadParamRequestException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WalletHistoryDTOValidator implements WalletHistoryDTOValidatorInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @throws BadParamRequestException
     */
    public function validate(WalletHistoryDTO $walletHistoryDTO): void
    {
        $errors = $this->validator->validate($walletHistoryDTO);

        if ($errors->count()) {
            throw new BadParamRequestException($errors);
        }
    }
}
