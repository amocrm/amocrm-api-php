<?php

declare(strict_types=1);

namespace AmoCRM\Models\Sources;

use AmoCRM\Models\BaseApiModel;

/**
 * Class WebsiteButtonUpdateRequestModel
 *
 * @package AmoCRM\Models\WebsiteButtons
 */
class WebsiteButtonUpdateRequestModel extends BaseApiModel
{
    /**
     * @var array $trustedWebsitesToAdd
     */
    private $trustedWebsitesToAdd;

    /**
     * @var int $trustedWebsitesToAdd
     */
    private $sourceId;

    public function __construct(
        array $trustedWebsitesToAdd,
        int $sourceId
    ) {
        $this->trustedWebsitesToAdd = $trustedWebsitesToAdd;
        $this->sourceId = $sourceId;
    }

    public function getTrustedWebsitesToAdd(): array
    {
        return $this->trustedWebsitesToAdd;
    }

    public function setTrustedWebsites(array $trustedWebsitesToAdd): void
    {
        $this->trustedWebsitesToAdd = $trustedWebsitesToAdd;
    }

    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    public function setSourceId(int $sourceId): void
    {
        $this->sourceId = $sourceId;
    }

    public function toArray(): array
    {
        return [
            'trusted_websites' => [
                'add' => $this->getTrustedWebsitesToAdd(),
            ],
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
