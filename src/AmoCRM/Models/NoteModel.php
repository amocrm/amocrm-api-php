<?php

namespace AmoCRM\Models;

use Illuminate\Contracts\Support\Arrayable;

class NoteModel extends BaseApiModel implements Arrayable
{
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
        //todo all fields

        return $model;
    }

    /**
     * @return string
     */
    public function getNoteType(): string
    {
        //todo exception
        return 'common_note';
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [
            'id' => $this->getId(),
            'entity_id' => $this->getEntityId(),
        ];

        return $result;
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
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
     * @param int|null $requestId
     * @return array
     */
    public function toApi(int $requestId = null): array
    {
        $result = [];

        if (!is_null($this->getEntityId())) {
            $result['entity_id'] = $this->getEntityId();
        }

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        $result['note_type'] = $this->getNoteType();

        return $result;
    }
}
