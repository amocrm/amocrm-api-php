<?php

namespace AmoCRM\Models;

use AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Traits\RequestIdTrait;
use AmoCRM\Contracts\Support\Arrayable;

/**
 * Class NoteModel
 *
 * @package AmoCRM\Models
 */
class NoteModel extends BaseApiModel implements Arrayable, HasIdInterface
{
    use RequestIdTrait;

    protected $modelClass = NoteModel::class;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $entityId;

    /**
     * @var null|int
     */
    protected $responsibleUserId;

    /**
     * @var null|int
     */
    protected $groupId;

    /**
     * @var null|int
     */
    protected $createdBy;

    /**
     * @var null|int
     */
    protected $updatedBy;

    /**
     * @var null|int
     */
    protected $createdAt;

    /**
     * @var null|int
     */
    protected $updatedAt;

    /**
     * @var null|int
     */
    protected $accountId;

    /**
     * @param array $note
     *
     * @return self
     */
    public function fromArray(array $note): self
    {
        /** @var NoteModel $model */
        $model = new $this->modelClass();

        $model->setId($note['id']);
        $model->setEntityId($note['entity_id']);

        if (array_key_exists('responsible_user_id', $note) && !is_null($note['responsible_user_id'])) {
            $model->setResponsibleUserId((int)$note['responsible_user_id']);
        }

        if (array_key_exists('group_id', $note) && !is_null($note['group_id'])) {
            $model->setGroupId((int)$note['group_id']);
        }

        if (array_key_exists('created_by', $note) && !is_null($note['created_by'])) {
            $model->setCreatedBy((int)$note['created_by']);
        }

        if (array_key_exists('updated_by', $note) && !is_null($note['updated_by'])) {
            $model->setUpdatedBy((int)$note['updated_by']);
        }

        if (!empty($note['created_at'])) {
            $model->setCreatedAt($note['created_at']);
        }

        if (!empty($note['updated_at'])) {
            $model->setUpdatedAt($note['updated_at']);
        }

        if (!empty($note['account_id'])) {
            $model->setAccountId((int)$note['account_id']);
        }

        return $model;
    }

    /**
     * @return string
     */
    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_COMMON;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        //Параметы в child-классах

        return [
            'id' => $this->getId(),
            'entity_id' => $this->getEntityId(),
            'note_type' => $this->getNoteType(),
            'responsible_user_id' => $this->getResponsibleUserId(),
            'group_id' => $this->getGroupId(),
            'created_by' => $this->getCreatedBy(),
            'updated_by' => $this->getUpdatedBy(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'account_id' => $this->getAccountId(),
        ];
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return NoteModel
     */
    public function setId(int $id): NoteModel
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->entityId;
    }

    /**
     * @param int $entityId
     * @return NoteModel
     */
    public function setEntityId(int $entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getResponsibleUserId(): ?int
    {
        return $this->responsibleUserId;
    }

    /**
     * @param int|null $responsibleUserId
     * @return NoteModel
     */
    public function setResponsibleUserId(?int $responsibleUserId): NoteModel
    {
        $this->responsibleUserId = $responsibleUserId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getGroupId(): ?int
    {
        return $this->groupId;
    }

    /**
     * @param int|null $groupId
     * @return NoteModel
     */
    public function setGroupId(?int $groupId): NoteModel
    {
        $this->groupId = $groupId;

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
     * @return NoteModel
     */
    public function setCreatedBy(?int $createdBy): NoteModel
    {
        $this->createdBy = $createdBy;

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
     * @return NoteModel
     */
    public function setUpdatedBy(?int $updatedBy): NoteModel
    {
        $this->updatedBy = $updatedBy;

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
     * @return NoteModel
     */
    public function setCreatedAt(?int $createdAt): NoteModel
    {
        $this->createdAt = $createdAt;

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
     * @return NoteModel
     */
    public function setUpdatedAt(?int $updatedAt): NoteModel
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    /**
     * @param int|null $accountId
     * @return NoteModel
     */
    public function setAccountId(?int $accountId): NoteModel
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = [];

        if (!is_null($this->getEntityId())) {
            $result['entity_id'] = $this->getEntityId();
        }

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        if (!is_null($this->getResponsibleUserId())) {
            $result['responsible_user_id'] = $this->getResponsibleUserId();
        }

        if (!is_null($this->getCreatedBy())) {
            $result['created_by'] = $this->getCreatedBy();
        }

        if (!is_null($this->getUpdatedBy())) {
            $result['updated_by'] = $this->getUpdatedBy();
        }

        if (!is_null($this->getCreatedAt())) {
            $result['created_at'] = $this->getCreatedAt();
        }

        if (!is_null($this->getUpdatedAt())) {
            $result['updated_at'] = $this->getUpdatedAt();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        $result['request_id'] = $this->getRequestId();

        $result['note_type'] = $this->getNoteType();

        return $result;
    }

    /**
     * @return array
     */
    public static function getAvailableWith(): array
    {
        return [];
    }
}
