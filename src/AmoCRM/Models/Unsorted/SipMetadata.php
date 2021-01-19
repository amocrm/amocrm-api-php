<?php

namespace AmoCRM\Models\Unsorted;

use AmoCRM\Models\Unsorted\Interfaces\UnsortedMetadataInterface;
use AmoCRM\Models\BaseApiModel;
use Illuminate\Contracts\Support\Arrayable;

class SipMetadata extends BaseApiModel implements Arrayable, UnsortedMetadataInterface
{
    /**
     * @var int|null
     */
    protected $from;

    /**
     * @var string|null
     */
    protected $phone;

    /**
     * @var int|null
     */
    protected $calledAt;

    /**
     * @var int|null
     */
    protected $duration;

    /**
     * @var string|null
     */
    protected $link;

    /**
     * @var string|null
     */
    protected $serviceCode;

    /**
     * @var string|null
     */
    protected $uniq;

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
        ];
    }

    /**
     * @return int|null
     */
    public function getFrom(): ?int
    {
        return $this->from;
    }

    /**
     * @param int|null $from
     * @return SipMetadata
     */
    public function setFrom(?int $from): SipMetadata
    {
        $this->from = $from;

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
     * @return SipMetadata
     */
    public function setPhone(?string $phone): SipMetadata
    {
        $this->phone = $phone;

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
     * @return int|null
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @param int|null $duration
     * @return SipMetadata
     */
    public function setDuration(?int $duration): SipMetadata
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
     * @return SipMetadata
     */
    public function setLink(?string $link): SipMetadata
    {
        $this->link = $link;

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
     * @return string|null
     */
    public function getUniq(): ?string
    {
        return $this->uniq;
    }

    /**
     * @param string|null $uniq
     * @return SipMetadata
     */
    public function setUniq(?string $uniq): SipMetadata
    {
        $this->uniq = $uniq;

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
        return [
            'from' => $this->getFrom(),
            'phone' => $this->getPhone(),
            'called_at' => $this->getCalledAt() ?? time(),
            'duration' => $this->getDuration(),
            'link' => $this->getLink(),
            'service_code' => $this->getServiceCode(),
            'uniq' => $this->getUniq(),
            'is_call_event_needed' => $this->getIsCallEventNeeded() ?? true,
        ];
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
