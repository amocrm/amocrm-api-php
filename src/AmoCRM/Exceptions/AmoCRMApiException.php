<?php

namespace AmoCRM\Exceptions;

use Exception;

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
}
