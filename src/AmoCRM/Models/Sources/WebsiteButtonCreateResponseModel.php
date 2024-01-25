<?php

declare(strict_types=1);

namespace AmoCRM\Models\Sources;

use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\BaseApiModel;

/**
 * Class WebsiteButtonCreateResponseModel
 *
 * @package AmoCRM\Models\WebsiteButtons
 */
class WebsiteButtonCreateResponseModel extends BaseApiModel
{
    /**
     * @var int $sourceId
     */
    private $sourceId;

    /**
     * @var array $trustedWebsites
     */
    private array $trustedWebsites;

    public function __construct(
        int $sourceId,
        array $trustedWebsites = []
    ) {
        $this->sourceId = $sourceId;
        $this->trustedWebsites = $trustedWebsites;
    }

    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    public function setSourceId(int $sourceId): void
    {
        $this->sourceId = $sourceId;
    }

    public function getTrustedWebsites(): array
    {
        return $this->trustedWebsites;
    }

    public function setTrustedWebsites(array $trustedWebsites): void
    {
        $this->trustedWebsites = $trustedWebsites;
    }

    /**
     * @param array $WebsiteButtonCreateResponseModel
     *
     * @return WebsiteButtonCreateResponseModel
     */
    public static function fromArray(array $websiteButtonCreateResponse): WebsiteButtonCreateResponseModel
    {
        $model = new WebsiteButtonCreateResponseModel(
            (int) ($websiteButtonCreateResponse['source_id'] ?? 0),
            (array) ($websiteButtonCreateResponse['trusted_websites'] ?? [])
        );

        return $model;
    }

    public function toArray(): array
    {
        return [
            'source_id' => $this->getSourceId(),
            'trusted_websites' => $this->getTrustedWebsites(),
        ];
    }

    /**
     * @param string|null $requestId
     * @return array
     * @throws NotAvailableForActionException
     */
    public function toApi(?string $requestId = '0'): array
    {
        throw new NotAvailableForActionException();
    }
}
