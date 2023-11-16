<?php

namespace AmoCRM\Models\Chats\Templates;

use AmoCRM\Models\BaseApiModel;

class ReviewModel extends BaseApiModel
{
    public const STATUS_DRAFT_NAME = 'draft';
    public const STATUS_REVIEW_NAME = 'review';
    public const STATUS_REJECTED_NAME = 'rejected';
    public const STATUS_APPROVED_NAME = 'approved';
    public const STATUS_PAUSED_NAME = 'paused';

    protected ?int $id = null;
    protected ?int $sourceId = null;
    protected ?string $status = null;
    protected ?string $rejectReason = null;

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
            $model->setStatus((string)$data['status']);
        }

        if (isset($data['reject_reason'])) {
            $model->setRejectReason((string)$data['reject_reason']);
        }

        return $model;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'source_id' => $this->getSourceId(),
            'status' => $this->getStatus(),
            'reject_reason' => $this->getRejectReason()
        ];
    }

    public function toApi(string $requestId = null): array
    {
        return $this->toArray();
    }

    /**
     * @param int|null $id
     * @return ReviewModel
     */
    public function setId(?int $id): ReviewModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param int|null $sourceId
     * @return ReviewModel
     */
    public function setSourceId(?int $sourceId): ReviewModel
    {
        $this->sourceId = $sourceId;
        return $this;
    }

    /**
     * @param string|null $status
     * @return ReviewModel
     */
    public function setStatus(?string $status): ReviewModel
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param string|null $rejectReason
     * @return ReviewModel
     */
    public function setRejectReason(?string $rejectReason): ReviewModel
    {
        $this->rejectReason = $rejectReason;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRejectReason(): ?string
    {
        return $this->rejectReason;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getSourceId(): ?int
    {
        return $this->sourceId;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }
}
