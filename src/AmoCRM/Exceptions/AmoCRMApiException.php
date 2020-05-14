<?php

namespace AmoCRM\Exceptions;

use Exception;
use Throwable;

/**
 * Class AmoCRMApiException
 *
 * @package AmoCRM\Exceptions
 */
class AmoCRMApiException extends Exception
{
    /**
     * @var int
     */
    protected $errorCode;

    /**
     * @var string
     */
    protected $description = "";

    /**
     * @var string
     */
    protected $title = "";

    /**
     * @var array
     */
    protected $lastRequestInfo = [];

    /**
     * AmoCRMApiException constructor.
     * @param string $message
     * @param int $code
     * @param array $lastRequestInfo
     * @param string $description
     * @param Throwable|null $previous
     */
    public function __construct(
        $message = "",
        $code = 0,
        array $lastRequestInfo = [],
        string $description = "",
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this
            ->setTitle($message)
            ->setErrorCode($code)
            ->setLastRequestInfo($lastRequestInfo)
            ->setDescription($description);
    }

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * @param int $errorCode
     * @return AmoCRMApiException
     */
    public function setErrorCode(int $errorCode): AmoCRMApiException
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return array|null
     */
    public function getLastRequestInfo(): array
    {
        return $this->lastRequestInfo;
    }

    /**
     * @param array $lastRequestInfo
     * @return AmoCRMApiException
     */
    public function setLastRequestInfo(array $lastRequestInfo): self
    {
        $this->lastRequestInfo = $lastRequestInfo;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return AmoCRMApiException
     */
    public function setDescription(string $description): AmoCRMApiException
    {
        $this->description = $description;

        return $this;
    }
}
