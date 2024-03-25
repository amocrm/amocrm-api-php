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
    protected $isSupportListMessage = false;

    public function toArray(): array
    {
        return [
            'waba' => $this->isWaba(),
            'is_supports_list_message' => $this->isSupportListMessage(),
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
            $params->setIsSupportListMessage((bool)$data['is_supports_list_message']);
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
     * @param bool $isSupportListMessage
     */
    public function setIsSupportListMessage(bool $isSupportListMessage): void
    {
        $this->isSupportListMessage = $isSupportListMessage;
    }

    /**
     * @return bool
     */
    public function isSupportListMessage(): bool
    {
        return $this->isSupportListMessage;
    }
}
