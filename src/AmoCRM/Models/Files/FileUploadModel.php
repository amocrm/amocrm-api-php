<?php

namespace AmoCRM\AmoCRM\Models\Files;

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

    public function toArray(): array
    {
        return [
            'local_path' => $this->getLocalPath(),
            'name' => $this->getName(),
            'file_uuid' => $this->getFileUuid(),
        ];
    }

    public function toApi(?string $requestId = "0"): array
    {
        return $this->toArray();
    }
}
