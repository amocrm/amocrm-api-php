<?php

declare(strict_types=1);

namespace AmoCRM\Models\Sources;

use AmoCRM\Models\BaseApiModel;

/**
 * Class WebsiteButtonCreateRequestModel
 *
 * @package AmoCRM\Models\Sources
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

    /**
     * WebsiteButtonCreateRequestModel constructor.
     *
     * @param int $pipelineId
     * @param array $trustedWebsites
     * @param bool $isUsedInApp
     */
    public function __construct(
        int $pipelineId,
        array $trustedWebsites = [],
        bool $isUsedInApp = false
    ) {
        $this->pipelineId = $pipelineId;
        $this->trustedWebsites = $trustedWebsites;
        $this->isUsedInApp = $isUsedInApp;
    }

    /**
     * @return int
     */
    public function getPipelineId(): int
    {
        return $this->pipelineId;
    }

    /**
     * @param int $pipelineId
     *
     * @return void
     */
    public function setPipelineId(int $pipelineId): void
    {
        $this->pipelineId = $pipelineId;
    }

    /**
     * @return bool
     */
    public function isUsedInApp(): bool
    {
        return $this->isUsedInApp;
    }

    /**
     * @param bool $isUsedInApp
     *
     * @return void
     */
    public function setIsUsedInApp(bool $isUsedInApp): void
    {
        $this->isUsedInApp = $isUsedInApp;
    }

    /**
     * @return array
     */
    public function getTrustedWebsites(): array
    {
        return $this->trustedWebsites;
    }

    /**
     * @param array $trustedWebsites
     *
     * @return void
     */
    public function setTrustedWebsites(array $trustedWebsites): void
    {
        $this->trustedWebsites = $trustedWebsites;
    }

    /**
     * @return array
     */
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
     *
     * @return array
     */
    public function toApi(?string $requestId = '0'): array
    {
        return $this->toArray();
    }
}
