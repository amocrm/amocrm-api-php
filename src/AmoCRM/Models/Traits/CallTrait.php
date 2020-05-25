<?php

namespace AmoCRM\Models\Traits;

use AmoCRM\Models\Interfaces\CallInterface;

trait CallTrait
{
    /**
     * @var null|string
     */
    protected $uniq;

    /**
     * @var null|string
     */
    protected $source;

    /**
     * @var int|string
     */
    protected $duration;

    /**
     * @var null|string
     */
    protected $link;

    /**
     * @var null|string
     */
    protected $phone;

    /**
     * @var null|string
     */
    protected $callResult;

    /**
     * @var null|int
     */
    protected $callStatus;

    /**
     * @return string|null
     */
    public function getUniq(): ?string
    {
        return $this->uniq;
    }

    /**
     * @param string|null $uniq
     * @return self
     */
    public function setUniq(?string $uniq): self
    {
        $this->uniq = $uniq;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * @param string|null $source
     * @return self
     */
    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return int|string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int|string $duration
     * @return self
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string|null $link
     * @return self
     */
    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return self
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCallResult(): ?string
    {
        return $this->callResult;
    }

    /**
     * @param string|null $callResult
     * @return self
     */
    public function setCallResult(?string $callResult): self
    {
        $this->callResult = $callResult;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCallStatus(): ?int
    {
        return $this->callStatus;
    }

    /**
     * @param int|null $callStatus
     * @return self
     */
    public function setCallStatus(?int $callStatus): self
    {
        if (!in_array($callStatus, CallInterface::AVAILABLE_CALL_STATUSES, true)) {
            return $this;
        }

        $this->callStatus = $callStatus;

        return $this;
    }
}
