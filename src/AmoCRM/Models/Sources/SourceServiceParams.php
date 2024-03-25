<?php

declare(strict_types=1);

namespace AmoCRM\Models\Sources;

use AmoCRM\Models\BaseApiModel;

class SourceServiceParams extends BaseApiModel
{
    /**
     * @var bool
     */
    protected $waba = null;
    /**
     * @var bool
     */
    protected $isSupportsListMessage = false;

    public function toArray(): array
    {
        return [
            'waba' => $this->isWaba(),
            'is_supports_list_message' => $this->isSupportsListMessage(),
        ];
    }

    public function toApi(string $requestId = null): array
    {
        return $this->toArray();
    }

    public static function fromArray(array $data): self
    {
        $params = new self();
        $params->setWaba($data['waba'] ?? false);
        if (isset($data['is_supports_list_message'])) {
            $params->setIsSupportsListMessage((bool)$data['is_supports_list_message']);
        }

        return $params;
    }

    /**
     * @return bool|null
     */
    public function isWaba(): ?bool
    {
        return $this->waba;
    }

    /**
     * @param bool $waba
     */
    public function setWaba(bool $waba): void
    {
        $this->waba = $waba;
    }

    /**
     * @param bool $isSupportsListMessage
     */
    public function setIsSupportsListMessage(bool $isSupportsListMessage): void
    {
        $this->isSupportsListMessage = $isSupportsListMessage;
    }

    /**
     * @return bool
     */
    public function isSupportsListMessage(): bool
    {
        return $this->isSupportsListMessage;
    }
}
