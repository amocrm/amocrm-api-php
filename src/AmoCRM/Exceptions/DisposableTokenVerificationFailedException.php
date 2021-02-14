<?php

declare(strict_types=1);

namespace AmoCRM\AmoCRM\Exceptions;

use AmoCRM\Exceptions\AmoCRMApiException;

/**
 * Class DisposableTokenVerificationFailedException
 *
 * Выбрасывается в случае неверной подписи токена
 *
 * @package AmoCRM\AmoCRM\Exceptions
 */
class DisposableTokenVerificationFailedException extends AmoCRMApiException
{
    public static function create(): self
    {
        return new self('Disposable token verification signature failed');
    }
}
