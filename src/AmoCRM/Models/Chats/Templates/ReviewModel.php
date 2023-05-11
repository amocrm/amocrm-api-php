<?php

namespace AmoCRM\Models\Chats\Templates;

use AmoCRM\Models\BaseApiModel;

class ReviewModel extends BaseApiModel
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $sourceId;

    /**
     * @var string
     */
    protected $status;

    public static function fromArray(array $data): ReviewModel
    {
        $model = new static();

        if (isset($data['id'])) {
            $model->setId((int)$data['id']);
        }

        if (isset($data['source_id'])) {
            $model->setSourceId((int)$data['source_id']);
        }

        if (isset($data['status'])) {
            $model->setStatus($data['status']);
        }

        return $model;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'source_id' => $this->getSourceId(),
            'status' => $this->getStatus()
        ];
    }

    public function toApi(string $requestId = null): array
    {
        return $this->toArray();
    }

    /**
     * @param int $id
     * @return ReviewModel
     */
    public function setId(int $id): ReviewModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param int $sourceId
     * @return ReviewModel
     */
    public function setSourceId(int $sourceId): ReviewModel
    {
        $this->sourceId = $sourceId;
        return $this;
    }

    /**
     * @param string $status
     * @return ReviewModel
     */
    public function setStatus(string $status): ReviewModel
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
