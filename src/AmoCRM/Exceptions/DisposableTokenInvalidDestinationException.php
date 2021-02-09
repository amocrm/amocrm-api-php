<?php

declare(strict_types=1);

namespace AmoCRM\AmoCRM\Exceptions;

use AmoCRM\Exceptions\AmoCRMApiException;

/**
 * Class DisposableTokenInvalidDestinationException
 *
 * Выбрасывается в случае, если был получен неверный адресат
 *
 * @package AmoCRM\AmoCRM\Exceptions
 */
class DisposableTokenInvalidDestinationException extends AmoCRMApiException
{
    public static function create(): self
    {
        return new self('Disposable token invalid destination');
    }
}
