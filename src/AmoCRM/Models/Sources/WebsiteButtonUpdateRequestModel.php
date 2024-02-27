<?php

declare(strict_types=1);

namespace AmoCRM\Models\Sources;

use AmoCRM\Models\BaseApiModel;
use InvalidArgumentException;

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
     * @var string|null
     */
    private $name;

    /**
     * WebsiteButtonUpdateRequestModel constructor.
     *
     * @param array $trustedWebsitesToAdd
     * @param int $sourceId
     */
    public function __construct(
        array $trustedWebsitesToAdd,
        int $sourceId,
        ?string $name = null
    ) {
        $this->trustedWebsitesToAdd = $trustedWebsitesToAdd;
        $this->sourceId = $sourceId;
        $this->name = $name;
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
        $attributes = [];

        if (!empty($this->getTrustedWebsitesToAdd())) {
            $attributes['trusted_websites']['add'] = $this->getTrustedWebsitesToAdd();
        }

        if (!empty($this->getName())) {
            $attributes['name'] = $this->getName();
        }

        return $attributes;
    }

    /**
     * @param string|null $requestId
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function toApi(?string $requestId = '0'): array
    {
        $data = $this->toArray();

        if (empty($data)) {
            throw new InvalidArgumentException('No attributes to update given');
        }

        return $data;
    }
}
