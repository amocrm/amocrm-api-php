<?php

namespace AmoCRM\Exceptions;

/**
 * Class AmoCRMApiHttpClientException
 *
 * Выбрасывается в случае ответа от сервера с кодом 429
 *
 * @package AmoCRM\Exceptions
 */
class AmoCRMApiTooManyRequestsException extends AmoCRMApiHttpClientException
{
}
