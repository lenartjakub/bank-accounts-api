<?php

declare(strict_types=1);

namespace App\Validator\DTO;

use App\DTO\WalletOperationDTOInterface;
use App\Exception\BadParamRequestException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WalletOperationDTOValidator implements WalletOperationDTOValidatorInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @throws BadParamRequestException
     */
    public function validate(WalletOperationDTOInterface $walletDTO): void
    {
        $errors = $this->validator->validate($walletDTO);

        if ($errors->count()) {
            throw new BadParamRequestException($errors);
        }
    }
}
