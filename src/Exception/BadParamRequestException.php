<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationList;

class BadParamRequestException extends Exception
{
    private ConstraintViolationList $errors;

    public function __construct(ConstraintViolationList $errors)
    {
        $this->errors = $errors;
    }

    public function getMessages(): array
    {
        $messages = [];
        foreach ($this->errors as $error) {
            $messages[$error->getPropertyPath()] = [$error->getMessage()];
        }

        return $messages;
    }
}
