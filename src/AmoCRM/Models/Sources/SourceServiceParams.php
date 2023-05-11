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

    public function toArray(): array
    {
        return [
            'waba' => $this->isWaba()
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
}
