<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Models\NoteModel;

class AttachmentNote extends NoteModel
{
    protected $modelClass = AttachmentNote::class;

    /**
     * @var null|string
     */
    protected $originalName;

    /**
     * @var null|string
     */
    protected $fileUuid;

    /**
     * @var null|string
     */
    protected $fileName;

    /**
     * @var null|string
     */
    protected $versionUuid;

    /**
     * @var null|bool
     */
    protected $isDriveAttachment;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_ATTACHMENT;
    }

    /**
     * @param array $note
     *
     * @return self
     */
    public function fromArray(array $note): NoteModel
    {
        /** @var self $model */
        $model = parent::fromArray($note);

        if (isset($note['params']['original_name'])) {
            $model->setOriginalName($note['params']['original_name']);
        }

        if (isset($note['params']['is_drive_attachment'])) {
            $model->setIsDriveAttachment($note['params']['is_drive_attachment']);
        }

        if (isset($note['params']['file_uuid'])) {
            $model->setFileUuid($note['params']['file_uuid']);
        }

        if (isset($note['params']['version_uuid'])) {
            $model->setVersionUuid($note['params']['version_uuid']);
        }

        if (isset($note['params']['file_name'])) {
            $model->setFileName($note['params']['file_name']);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['params']['original_name'] = $this->getOriginalName();
        $result['params']['version_uuid'] = $this->getVersionUuid();
        $result['params']['file_uuid'] = $this->getFileUuid();
        $result['params']['file_name'] = $this->getFileName();
        $result['params']['is_drive_attachment'] = $this->getIsDriveAttachment();

        return $result;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = parent::toApi($requestId);

        $result['params'] = [
            'version_uuid' => $this->getVersionUuid(),
            'file_uuid' => $this->getFileUuid(),
            'file_name' => $this->getFileName(),
        ];

        return $result;
    }

    /**
     * @return string|null
     */
    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    /**
     * @param string|null $originalName
     * @return AttachmentNote
     */
    public function setOriginalName(?string $originalName): AttachmentNote
    {
        $this->originalName = $originalName;

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
     * @return AttachmentNote
     */
    public function setFileUuid(?string $fileUuid): AttachmentNote
    {
        $this->fileUuid = $fileUuid;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string|null $fileName
     *
     * @return AttachmentNote
     */
    public function setFileName(?string $fileName): AttachmentNote
    {
        $this->fileName = $fileName;

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
     * @return AttachmentNote
     */
    public function setVersionUuid(?string $versionUuid): AttachmentNote
    {
        $this->versionUuid = $versionUuid;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsDriveAttachment(): ?bool
    {
        return $this->isDriveAttachment;
    }

    /**
     * @param bool|null $isDriveAttachment
     *
     * @return AttachmentNote
     */
    public function setIsDriveAttachment(?bool $isDriveAttachment): AttachmentNote
    {
        $this->isDriveAttachment = $isDriveAttachment;

        return $this;
    }
}
