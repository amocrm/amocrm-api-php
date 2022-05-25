<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

/**
 * @since Release Spring 2022
 */
class FileCustomFieldValueModel extends BaseCustomFieldValueModel
{
    /** @var null|string */
    private $fileUuid;
    /** @var null|string */
    private $versionUuid;
    /** @var null|string */
    private $fileName;
    /** @var null|int */
    private $fileSize;

    /**
     * @param null|string $fileUuid
     */
    public function setFileUuid(?string $fileUuid): void
    {
        $this->fileUuid = $fileUuid;
    }

    /**
     * @return null|string
     */
    public function getFileUuid(): ?string
    {
        return $this->fileUuid;
    }

    /**
     * @param null|string $versionUuid
     */
    public function setVersionUuid(?string $versionUuid): void
    {
        $this->versionUuid = $versionUuid;
    }

    /**
     * @return null|string
     */
    public function getVersionUuid(): ?string
    {
        return $this->versionUuid;
    }

    /**
     * @param null|string $fileName
     */
    public function setFileName(?string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return null|string
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param null|int $fileSize
     */
    public function setFileSize(?int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return null|int
     */
    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    /**
     * @return array{value: array{file_uuid?: string, version_uuid?: string, file_name?: string, file_size?: int}}
     */
    public function toArray(): array
    {
        return [
            'value' => [
                'file_uuid' => $this->getFileUuid(),
                'version_uuid' => $this->getVersionUuid(),
                'file_name' => $this->getFileName(),
                'file_size' => $this->getFileSize(),
            ],
        ];
    }

    /**
     * @return array{value: array{file_uuid?: string, version_uuid?: string, file_name?: string, file_size?: int}}
     */
    public function toApi(string $requestId = null): array
    {
        return [
            'value' => [
                'file_uuid' => $this->getFileUuid(),
                'version_uuid' => $this->getVersionUuid(),
                'file_name' => $this->getFileName(),
                'file_size' => $this->getFileSize(),
            ],
        ];
    }

    /**
     * @param array $value
     *
     * @return BaseCustomFieldValueModel
     */
    public static function fromArray($value): BaseCustomFieldValueModel
    {
        $val = (array) ($value['value'] ?? []);
        $model = new static();
        $model->setFileUuid((string) ($val['file_uuid'] ?? '') ?: null);
        $model->setVersionUuid((string) ($val['version_uuid'] ?? '') ?: null);
        $model->setFileName((string) ($val['file_name'] ?? '') ?: null);
        $model->setFileSize(isset($val['file_size']) ? (int) $val['file_size'] : null);

        return $model;
    }
}
