<?php

declare(strict_types=1);

namespace AmoCRM\Models\Sources;

use AmoCRM\Models\BaseApiModel;

/**
 * Class WebsiteButtonCreateRequestModel
 *
 * @package AmoCRM\Models\WebsiteButtons
 */
class WebsiteButtonCreateRequestModel extends BaseApiModel
{
    /**
     * @var int $pipelineId
     */
    private $pipelineId;

    /**
     * @var bool $isUsedInApp
     */
    private $isUsedInApp;

    /**
     * @var array $trustedWebsites
     */
    private $trustedWebsites;

    public function __construct(
        int $pipelineId,
        array $trustedWebsites = [],
        bool $isUsedInApp = false
    ) {
        $this->pipelineId = $pipelineId;
        $this->trustedWebsites = $trustedWebsites;
        $this->isUsedInApp = $isUsedInApp;
    }

    public function getPipelineId(): int
    {
        return $this->pipelineId;
    }

    public function setPipelineId(int $pipelineId): void
    {
        $this->pipelineId = $pipelineId;
    }

    public function isUsedInApp(): bool
    {
        return $this->isUsedInApp;
    }

    public function setIsUsedInApp(bool $isUsedInApp): void
    {
        $this->isUsedInApp = $isUsedInApp;
    }

    public function getTrustedWebsites(): array
    {
        return $this->trustedWebsites;
    }

    public function setTrustedWebsites(array $trustedWebsites): void
    {
        $this->trustedWebsites = $trustedWebsites;
    }

    public function toArray(): array
    {
        return [
            'pipeline_id' => $this->getPipelineId(),
            'is_used_in_app' => $this->isUsedInApp(),
            'trusted_websites' => $this->getTrustedWebsites(),
        ];
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = '0'): array
    {
        return $this->toArray();
    }
}
