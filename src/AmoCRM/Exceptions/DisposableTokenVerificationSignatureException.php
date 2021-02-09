<?php

declare(strict_types=1);

namespace AmoCRM\AmoCRM\Exceptions;

use AmoCRM\Exceptions\AmoCRMApiException;

/**
 * Class DisposableTokenVerificationSignatureException
 *
 * Выбрасывается в случае неверной подписи токена
 *
 * @package AmoCRM\AmoCRM\Exceptions
 */
class DisposableTokenVerificationSignatureException extends AmoCRMApiException
{
    public static function create(): self
    {
        return new self('Disposable token verification signature failed');
    }
}
