<?php

namespace AmoCRM\Models\Files;

use AmoCRM\Contracts\Support\Arrayable;
use AmoCRM\Models\BaseApiModel;

class FileUploadModel extends BaseApiModel implements Arrayable
{
    /** @var string */
    protected $localPath;

    /** @var string|null */
    protected $name;

    /** @var string|null */
    protected $fileUuid;

    /** @var int|null */
    protected $createdBy;

    /** @var string|null */
    protected $createdByType;

    /** @var bool */
    protected $withPreview = false;

    /**
     * @return string
     */
    public function getLocalPath(): string
    {
        return $this->localPath;
    }

    /**
     * @param string $localPath
     *
     * @return FileUploadModel
     */
    public function setLocalPath(string $localPath): FileUploadModel
    {
        $this->localPath = $localPath;

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
     * @param string|null $name
     *
     * @return FileUploadModel
     */
    public function setName(?string $name): FileUploadModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFileUuid(): ?string
    {
        return $this->fileUuid;
    }

    /**
     * @param string|null $fileUuid
     *
     * @return FileUploadModel
     */
    public function setFileUuid(?string $fileUuid): FileUploadModel
    {
        $this->fileUuid = $fileUuid;

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
     * @return FileUploadModel
     */
    public function setCreatedBy(?int $createdBy): FileUploadModel
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @param bool $withPreview
     *
     * @return FileUploadModel
     */
    public function setWithPreview(bool $withPreview): FileUploadModel
    {
        $this->withPreview = $withPreview;

        return $this;
    }

     /**
     * @return bool
     */
    public function isWithPreview(): bool
    {
        return $this->withPreview;
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
     * @return FileUploadModel
     */
    public function setCreatedByType(?string $createdByType): FileUploadModel
    {
        $this->createdByType = $createdByType;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'local_path' => $this->getLocalPath(),
            'name' => $this->getName(),
            'file_uuid' => $this->getFileUuid(),
            'created_by' => [
                'id' => $this->getCreatedBy(),
                'type' => $this->getCreatedByType(),
            ],
            'with_preview' => $this->isWithPreview(),
        ];
    }

    public function toApi(?string $requestId = "0"): array
    {
        $result = [
            'local_path' => $this->getLocalPath(),
            'name' => $this->getName(),
            'file_uuid' => $this->getFileUuid(),
            'with_preview' => $this->isWithPreview(),
        ];

        if (!is_null($this->getCreatedByType()) && !is_null($this->getCreatedBy())) {
            $result['created_by'] = [
                'id' => $this->getCreatedBy(),
                'type' =>  $this->getCreatedByType(),
            ];
        }

        return $result;
    }
}
