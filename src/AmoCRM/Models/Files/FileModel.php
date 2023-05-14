<?php

namespace AmoCRM\Models\Files;

use AmoCRM\Collections\FilePreviewsCollection;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Traits\RequestIdTrait;

use function sprintf;

class FileModel extends BaseApiModel implements HasIdInterface
{
    use RequestIdTrait;

    public const DELETED = 'deleted';
    public const UNBILLED = 'unbilled';

    /**
     * @var null|int
     */
    protected $id;

    /**
     * @var null|string
     */
    protected $uuid;

    /**
     * @var null|string
     */
    protected $versionUuid;

    /**
     * @var bool
     */
    protected $versionUuidChanged = false;

    /**
     * @var null|string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $nameChanged = false;

    /**
     * @var null|int
     */
    protected $size;

    /**
     * @var string|null
     */
    protected $mimeType;

    /**
     * @var string|null
     */
    protected $extension;

    /**
     * @var int|null
     */
    protected $createdAt;

    /**
     * @var int|null
     */
    protected $createdBy;

    /**
     * @var bool
     */
    protected $createdByChanged = false;

    /**
     * @var string|null
     */
    protected $createdByType;

    /**
     * @var int|null
     */
    protected $updatedAt;

    /**
     * @var int|null
     */
    protected $updatedBy;

    /**
     * @var bool
     */
    protected $updatedByChanged = false;

    /**
     * @var string|null
     */
    protected $updatedByType;

    /**
     * @var int|null
     */
    protected $deletedAt;

    /**
     * @var int|null
     */
    protected $deletedBy;

    /**
     * @var string|null
     */
    protected $deletedByType;

    /**
     * @var bool|null
     */
    protected $hasMultipleVersions;

    /**
     * @var bool|null
     */
    protected $isTrashed;

    /**
     * @var string|null
     */
    protected $sanitizedName;

    /**
     * @var int|null
     */
    protected $sourceId;

    /**
     * @var string|null
     */
    protected $downloadLink;

    /**
     * @var string|null
     */
    protected $downloadVersionLink;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var FilePreviewsCollection|null
     */
    protected $previews;

    /**
     * @return FilePreviewsCollection|null
     */
    public function getPreviews(): ?FilePreviewsCollection
    {
        return $this->previews;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return FileModel
     */
    public function setId(?int $id): FileModel
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @param string|null $uuid
     *
     * @return FileModel
     */
    public function setUuid(?string $uuid): FileModel
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVersionUuid(): ?string
    {
        return $this->versionUuid;
    }

    /**
     * @param string|null $versionUuid
     *
     * @return FileModel
     */
    public function setVersionUuid(?string $versionUuid): FileModel
    {
        $this->versionUuid = $versionUuid;
        $this->versionUuidChanged = true;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * @return string
     */
    public function getNameWithExtension(): string
    {
        return sprintf('%s.%s', $this->getName(), $this->getExtension());
    }

    /**
     * @param string|null $name
     *
     * @return FileModel
     */
    public function setName(?string $name): FileModel
    {
        $this->name = $name;
        $this->nameChanged = true;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * @param int|null $size
     *
     * @return FileModel
     */
    public function setSize(?int $size): FileModel
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param string|null $mimeType
     *
     * @return FileModel
     */
    public function setMimeType(?string $mimeType): FileModel
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * @param string|null $extension
     *
     * @return FileModel
     */
    public function setExtension(?string $extension): FileModel
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    /**
     * @param int|null $createdAt
     *
     * @return FileModel
     */
    public function setCreatedAt(?int $createdAt): FileModel
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    /**
     * @param int|null $createdBy
     *
     * @return FileModel
     */
    public function setCreatedBy(?int $createdBy): FileModel
    {
        $this->createdBy = $createdBy;
        $this->createdByChanged = true;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreatedByType(): ?string
    {
        return $this->createdByType;
    }

    /**
     * @param string|null $createdByType
     *
     * @return FileModel
     */
    public function setCreatedByType(?string $createdByType): FileModel
    {
        $this->createdByType = $createdByType;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getUpdatedAt(): ?int
    {
        return $this->updatedAt;
    }

    /**
     * @param int|null $updatedAt
     *
     * @return FileModel
     */
    public function setUpdatedAt(?int $updatedAt): FileModel
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    /**
     * @param int|null $updatedBy
     *
     * @return FileModel
     */
    public function setUpdatedBy(?int $updatedBy): FileModel
    {
        $this->updatedBy = $updatedBy;
        $this->updatedByChanged = true;

        return $this;
    }

    /**
     * @param FilePreviewsCollection|null $previews
     *
     * @return FileModel
     */
    public function setPreviews(?FilePreviewsCollection $previews): FileModel
    {
        $this->previews = $previews;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUpdatedByType(): ?string
    {
        return $this->updatedByType;
    }

    /**
     * @param string|null $updatedByType
     *
     * @return FileModel
     */
    public function setUpdatedByType(?string $updatedByType): FileModel
    {
        $this->updatedByType = $updatedByType;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDeletedAt(): ?int
    {
        return $this->deletedAt;
    }

    /**
     * @param int|null $deletedAt
     *
     * @return FileModel
     */
    public function setDeletedAt(?int $deletedAt): FileModel
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDeletedBy(): ?int
    {
        return $this->deletedBy;
    }

    /**
     * @param int|null $deletedBy
     *
     * @return FileModel
     */
    public function setDeletedBy(?int $deletedBy): FileModel
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    public function getDeletedByType(): ?string
    {
        return $this->deletedByType;
    }

    public function setDeletedByType(?string $deletedByType): FileModel
    {
        $this->deletedByType = $deletedByType;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getHasMultipleVersions(): ?bool
    {
        return $this->hasMultipleVersions;
    }

    /**
     * @param bool|null $hasMultipleVersions
     *
     * @return FileModel
     */
    public function setHasMultipleVersions(?bool $hasMultipleVersions): FileModel
    {
        $this->hasMultipleVersions = $hasMultipleVersions;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsTrashed(): ?bool
    {
        return $this->isTrashed;
    }

    /**
     * @param bool|null $isTrashed
     *
     * @return FileModel
     */
    public function setIsTrashed(?bool $isTrashed): FileModel
    {
        $this->isTrashed = $isTrashed;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSanitizedName(): ?string
    {
        return $this->sanitizedName;
    }

    /**
     * @param string|null $sanitizedName
     *
     * @return FileModel
     */
    public function setSanitizedName(?string $sanitizedName): FileModel
    {
        $this->sanitizedName = $sanitizedName;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getSourceId(): ?int
    {
        return $this->sourceId;
    }

    /**
     * @param int|null $sourceId
     *
     * @return FileModel
     */
    public function setSourceId(?int $sourceId): FileModel
    {
        $this->sourceId = $sourceId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDownloadLink(): ?string
    {
        return $this->downloadLink;
    }

    /**
     * @param string|null $downloadLink
     *
     * @return FileModel
     */
    public function setDownloadLink(?string $downloadLink): FileModel
    {
        $this->downloadLink = $downloadLink;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDownloadVersionLink(): ?string
    {
        return $this->downloadVersionLink;
    }

    /**
     * @param string|null $downloadVersionLink
     *
     * @return FileModel
     */
    public function setDownloadVersionLink(?string $downloadVersionLink): FileModel
    {
        $this->downloadVersionLink = $downloadVersionLink;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     *
     * @return FileModel
     */
    public function setType(?string $type): FileModel
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param array $file
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $file): self
    {
        if (empty($file['id']) || empty($file['uuid'])) {
            throw new InvalidArgumentException('File id/uuid is empty in ' . json_encode($file));
        }

        $isDeleted = !empty($file['deleted_by']['id']) || !empty($file['deleted_by']['type']);
        $fileModel = new self();
        $fileModel->setId((int)$file['id'])
            ->setVersionUuid($file['version_uuid'])
            ->setName($file['name'])
            ->setUuid($file['uuid'])
            ->setSize($file['size'])
            ->setDownloadLink($file['_links']['download']['href'])
            ->setDownloadVersionLink($file['_links']['download_version']['href'])
            ->setCreatedAt($file['created_at'])
            ->setCreatedBy($file['created_by']['id'])
            ->setCreatedByType($file['created_by']['type'])
            ->setUpdatedAt($file['updated_at'])
            ->setUpdatedBy($file['updated_by']['id'])
            ->setUpdatedByType($file['updated_by']['type'])
            ->setDeletedAt($file['deleted_at'])
            ->setDeletedBy($isDeleted ? (int)$file['deleted_by']['id'] : null)
            ->setDeletedByType($isDeleted ? (string)$file['deleted_by']['type'] : null)
            ->setHasMultipleVersions($file['has_multiple_versions'] ?? false)
            ->setIsTrashed($file['is_trashed'])
            ->setExtension($file['metadata']['extension'])
            ->setMimeType($file['metadata']['mime_type'])
            ->setSanitizedName($file['sanitized_name'])
            ->setSourceId($file['source_id'])
            ->setPreviews($file['previews'] ? FilePreviewsCollection::fromArray($file['previews']) : null)
            ->setType($file['type']);

        return $fileModel;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'uuid' => $this->getUuid(),
            'version_uuid' => $this->getVersionUuid(),
            'name' => $this->getName(),
            'size' => $this->getSize(),
            'download_link' => $this->getDownloadLink(),
            'download_version_link' => $this->getDownloadVersionLink(),
            'created_at' => $this->getCreatedAt(),
            'created_by' => [
                'id' => $this->getCreatedBy(),
                'type' => $this->getCreatedByType(),
            ],
            'updated_at' => $this->getUpdatedAt(),
            'updated_by' => [
                'id' => $this->getUpdatedBy(),
                'type' => $this->getUpdatedByType(),
            ],
            'deleted_at' => $this->getDeletedAt(),
            'deleted_by' => empty($this->getDeletedBy()) && empty($this->getDeletedByType())
                ? null
                : [
                    'id' => $this->getDeletedBy(),
                    'type' => $this->getDeletedByType(),
                ],
            'has_multiple_version' => $this->getHasMultipleVersions(),
            'is_trashed' => $this->getIsTrashed(),
            'extension' => $this->getExtension(),
            'previews' => $this->getPreviews(),
            'mime_type' => $this->getMimeType(),
            'sanitized_name' => $this->getSanitizedName(),
            'source_id' => $this->getSourceId(),
            'type' => $this->getType(),
        ];
    }

    public function toApi(?string $requestId = "0"): array
    {
        $result = [];

        $result['uuid'] = $this->getUuid();

        if ($this->nameChanged && !is_null($this->getName())) {
            $result['name'] = $this->getName();
        }

        if ($this->versionUuidChanged && !is_null($this->getVersionUuid())) {
            $result['version_uuid'] = $this->getVersionUuid();
        }

        if ($this->updatedByChanged && !is_null($this->getUpdatedBy()) && !is_null($this->getUpdatedByType())) {
            $result['updated_by'] = [
                'id' => $this->getUpdatedBy(),
                'type' => $this->getUpdatedByType(),
            ];
        }

        if ($this->createdByChanged && !is_null($this->getCreatedBy()) && !is_null($this->getCreatedByType())) {
            $result['created_by'] = [
                'id' => $this->getCreatedBy(),
                'type' => $this->getCreatedByType(),
            ];
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        $result['request_id'] = $this->getRequestId();

        $this->createdByChanged = false;
        $this->updatedByChanged = false;
        $this->versionUuidChanged = false;
        $this->nameChanged = false;

        return $result;
    }


    public function toDeleteApi(): array
    {
        $result = [];

        $result['uuid'] = $this->getUuid();

        return $result;
    }

    /**
     * @return array
     */
    public static function getAvailableWith(): array
    {
        return [
            self::DELETED,
            self::UNBILLED,
        ];
    }
}
