<?php

declare(strict_types=1);

namespace AmoCRM\Exceptions;

/**
 * Class DisposableTokenInvalidDestinationException
 *
 * Выбрасывается в случае, если был получен неверный адресат
 *
 * @package AmoCRM\Exceptions
 */
class DisposableTokenInvalidDestinationException extends AmoCRMApiException
{
    public static function create(): self
    {
        return new self('Disposable token invalid destination');
    }
}
