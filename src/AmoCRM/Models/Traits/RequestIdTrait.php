<?php

namespace AmoCRM\Models\Traits;

/**
 * Trait RequestIdTrait
 *
 * @package AmoCRM\Models\Traits
 */
trait RequestIdTrait
{
    /**
     * @var string|null
     */
    protected $requestId;

    /**
     * @return string|null
     */
    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    /**
     * @param string|int|null $requestId
     * @return self
     */
    public function setRequestId($requestId = null): self
    {
        $this->requestId = (string)$requestId;

        return $this;
    }
}
