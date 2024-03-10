<?php

declare(strict_types=1);

namespace AmoCRM\Models\Sources;

use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\BaseApiModel;

/**
 * Class WebsiteButtonCreateResponseModel
 *
 * @package AmoCRM\Models\Sources
 */
class WebsiteButtonCreateResponseModel extends BaseApiModel
{
    /**
     * @var int
     */
    private $sourceId;

    /**
     * @var array
     */
    private $trustedWebsites;

    /**
     * @var string|null
     */
    private $name;

    /**
     * WebsiteButtonCreateResponseModel constructor.
     *
     * @param int $sourceId
     * @param array $trustedWebsites
     * @param string|null $name
     */
    public function __construct(
        int $sourceId,
        array $trustedWebsites = [],
        ?string $name = null
    ) {
        $this->sourceId = $sourceId;
        $this->trustedWebsites = $trustedWebsites;
        $this->name = $name;
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
     * @param array $websiteButtonCreateResponse
     *
     * @return WebsiteButtonCreateResponseModel
     */
    public static function fromArray(array $websiteButtonCreateResponse): WebsiteButtonCreateResponseModel
    {
        return new WebsiteButtonCreateResponseModel(
            (int) ($websiteButtonCreateResponse['source_id'] ?? 0),
            (array) ($websiteButtonCreateResponse['trusted_websites'] ?? []),
            (string) ($websiteButtonCreateResponse['name'] ?? '') ?: null
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'source_id' => $this->getSourceId(),
            'trusted_websites' => $this->getTrustedWebsites(),
            'name' => $this->getName(),
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
