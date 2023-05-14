<?php

declare(strict_types=1);

namespace AmoCRM\Models;

class FileLinkModel extends BaseApiModel
{
    /** @var string|null */
    protected $fileUuid;

    /**
     * @return null|string
     */
    public function getFileUuid(): ?string
    {
        return $this->fileUuid;
    }

    /**
     * @param null|string $fileUuid
     *
     * @return FileLinkModel
     */
    public function setFileUuid(?string $fileUuid): FileLinkModel
    {
        $this->fileUuid = $fileUuid;

        return $this;
    }

    /**
     * @param array $link
     *
     * @return self
     */
    public static function fromArray(array $link): self
    {
        $model = new self();

        $model
            ->setFileUuid($link['file_uuid'] ?? null);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'file_uuid' => $this->getFileUuid(),
        ];
    }

    public function toApi(?string $requestId = "0"): array
    {
        return $this->toArray();
    }
}
