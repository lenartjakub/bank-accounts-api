<?php

declare(strict_types=1);

namespace App\Validator\DTO;

use App\DTO\WalletDTO;
use App\Exception\BadParamRequestException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WalletDTOValidator implements WalletDTOValidatorInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @throws BadParamRequestException
     */
    public function validate(WalletDTO $walletDTO): void
    {
        $errors = $this->validator->validate($walletDTO);

        if ($errors->count()) {
            throw new BadParamRequestException($errors);
        }
    }
}
