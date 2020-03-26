<?php

namespace AmoCRM\Exceptions;

class AmoCRMApiException extends \Exception
{
    /**
     * @var int
     */
    protected $errorCode;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $title;
}
