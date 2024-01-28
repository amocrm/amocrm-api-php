<?php

declare(strict_types=1);

namespace AmoCRM\Models\Sources;

use AmoCRM\Models\BaseApiModel;

/**
 * Class WebsiteButtonUpdateRequestModel
 *
 * @package AmoCRM\Models\Sources
 */
class WebsiteButtonUpdateRequestModel extends BaseApiModel
{
    /**
     * @var array
     */
    private $trustedWebsitesToAdd;

    /**
     * @var int
     */
    private $sourceId;

    /**
     * WebsiteButtonUpdateRequestModel constructor.
     *
     * @param array $trustedWebsitesToAdd
     * @param int $sourceId
     */
    public function __construct(
        array $trustedWebsitesToAdd,
        int $sourceId
    ) {
        $this->trustedWebsitesToAdd = $trustedWebsitesToAdd;
        $this->sourceId = $sourceId;
    }

    /**
     * @return array
     */
    public function getTrustedWebsitesToAdd(): array
    {
        return $this->trustedWebsitesToAdd;
    }

    /**
     * @param array $trustedWebsitesToAdd
     *
     * @return void
     */
    public function setTrustedWebsites(array $trustedWebsitesToAdd): void
    {
        $this->trustedWebsitesToAdd = $trustedWebsitesToAdd;
    }

    /**
     * @return int
     */
    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    /**
     * @param int $sourceId
     *
     * @return void
     */
    public function setSourceId(int $sourceId): void
    {
        $this->sourceId = $sourceId;
    }

    /**
     * @return array
     */
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
