<?php

namespace AmoCRM\Exceptions;

/**
 * Class AmoCRMApiErrorResponseException
 *
 * Выбрасывается в случае ошибки запроса
 *
 * @package AmoCRM\Exceptions
 */
class AmoCRMApiErrorResponseException extends AmoCRMApiException
{
    /**
     * @var array
     */
    protected $validationErrors = [];

    /**
     * @param array $errors
     * @return $this
     */
    public function setValidationErrors(array $errors): self
    {
        $this->validationErrors = $errors;

        return $this;
    }

    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}
