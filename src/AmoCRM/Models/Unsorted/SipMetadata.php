<?php

namespace AmoCRM\Models\Unsorted;

use AmoCRM\Models\Traits\CallTrait;
use AmoCRM\Models\Unsorted\Interfaces\UnsortedMetadataInterface;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Contracts\Support\Arrayable;

class SipMetadata extends BaseApiModel implements Arrayable, UnsortedMetadataInterface
{
    use CallTrait;

    /**
     * @deprecated
     * @var int|string|null
     */
    protected $from;

    /**
     * @var int|null
     */
    protected $calledAt;

    /**
     * @var string|null
     */
    protected $serviceCode;

    /**
     * @var bool|null
     */
    protected $isCallEventNeeded;

    /**
     * @param array $metadata
     *
     * @return self
     */
    public static function fromArray(array $metadata): self
    {
        $model = new self();

        $model->setFrom($metadata['from'] ?? null);
        $model->setPhone($metadata['phone'] ?? null);
        $model->setCalledAt($metadata['called_at'] ?? null);
        $model->setDuration($metadata['duration'] ?? null);
        $model->setLink($metadata['link'] ?? null);
        $model->setServiceCode($metadata['service_code'] ?? null);
        $model->setUniq($metadata['uniq'] ?? null);
        $model->setCallResponsible($metadata['call_responsible'] ?? null);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'from' => $this->getFrom(),
            'phone' => $this->getPhone(),
            'called_at' => $this->getCalledAt(),
            'duration' => $this->getDuration(),
            'link' => $this->getLink(),
            'service_code' => $this->getServiceCode(),
            'uniq' => $this->getUniq(),
            'call_responsible' => $this->getCallResponsible(),
        ];
    }

    /**
     * @deprecated
     * @return int|string|null
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @deprecated
     * @param int|null|string $from
     * @return SipMetadata
     */
    public function setFrom($from): SipMetadata
    {
        $this->from = is_string($from) || is_int($from) ? $from : null;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCalledAt(): ?int
    {
        return $this->calledAt;
    }

    /**
     * @param int|null $calledAt
     * @return SipMetadata
     */
    public function setCalledAt(?int $calledAt): SipMetadata
    {
        $this->calledAt = $calledAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getServiceCode(): ?string
    {
        return $this->serviceCode;
    }

    /**
     * @param string|null $serviceCode
     * @return SipMetadata
     */
    public function setServiceCode(?string $serviceCode): SipMetadata
    {
        $this->serviceCode = $serviceCode;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsCallEventNeeded(): ?bool
    {
        return $this->isCallEventNeeded;
    }

    /**
     * @param bool|null $isCallEventNeeded
     *
     * @return SipMetadata
     */
    public function setIsCallEventNeeded(?bool $isCallEventNeeded): SipMetadata
    {
        $this->isCallEventNeeded = $isCallEventNeeded;

        return $this;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = [
            'phone' => $this->getPhone(),
            'called_at' => $this->getCalledAt() ?? time(),
            'duration' => $this->getDuration(),
            'link' => $this->getLink(),
            'service_code' => $this->getServiceCode(),
            'uniq' => $this->getUniq(),
            'is_call_event_needed' => $this->getIsCallEventNeeded() ?? true,
            'call_responsible' => $this->getCallResponsible(),
        ];

        if (!empty($this->getFrom())) {
            $result['from'] = $this->getFrom();
        }

        return $result;
    }

    /**
     * @return array
     */
    public function toComplexApi(): array
    {
        $result = $this->toApi();

        $result['category'] = BaseUnsortedModel::CATEGORY_CODE_SIP;

        return $result;
    }
}
