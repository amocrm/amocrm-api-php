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
     * @var string|null $name
     */
    private $name;

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
        bool $isUsedInApp = false,
        ?string $name = null
    ) {
        $this->pipelineId = $pipelineId;
        $this->trustedWebsites = $trustedWebsites;
        $this->isUsedInApp = $isUsedInApp;
        $this->name = $name;
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $attributes = [
            'pipeline_id' => $this->getPipelineId(),
            'is_used_in_app' => $this->isUsedInApp(),
            'trusted_websites' => $this->getTrustedWebsites(),
        ];

        if (!empty($this->getName())) {
            $attributes['name'] = $this->getName();
        }

        return $attributes;
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
