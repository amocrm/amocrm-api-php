<?php

namespace AmoCRM\AmoCRM\Exceptions;

use AmoCRM\Exceptions\AmoCRMApiException;

/**
 * Class DisposableTokenVerificationFailedException
 *
 * Выбрасывается в случае неверного переданного одноразового токена
 *
 * @package AmoCRM\AmoCRM\Exceptions
 */
class DisposableTokenVerificationFailedException extends AmoCRMApiException
{
}
