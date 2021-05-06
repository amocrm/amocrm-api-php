<?php

declare(strict_types=1);

namespace AmoCRM\Exceptions;

/**
 * Class InvalidDisposableTokenException
 *
 * Выбрасывается в случае, если истёк срок жизни токена
 *
 * @package AmoCRM\Exceptions
 */
class DisposableTokenExpiredException extends AmoCRMApiException
{
    public static function create(): self
    {
        return new self('Disposable token expired');
    }
}
