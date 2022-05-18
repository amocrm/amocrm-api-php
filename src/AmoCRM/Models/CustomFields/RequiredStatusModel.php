<?php

namespace AmoCRM\Models\CustomFields;

use AmoCRM\Models\BaseApiModel;
use AmoCRM\Contracts\Support\Arrayable;

/**
 * Class RequiredStatusModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class RequiredStatusModel extends BaseApiModel implements Arrayable
{
    /**
     * @var int|null
     */
    protected $statusId;

    /**
     * @var int|null
     */
    protected $pipelineId;

    /**
     * @param array $status
     *
     * @return self
     */
    public static function fromArray(array $status): self
    {
        $model = new self();

        $model
            ->setPipelineId($status['pipeline_id'])
            ->setStatusId($status['status_id']);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'status_id' => $this->getStatusId(),
            'pipeline_id' => $this->getPipelineId(),
        ];
    }

    /**
     * @return int|null
     */
    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    /**
     * @param int|null $statusId
     *
     * @return RequiredStatusModel
     */
    public function setStatusId(?int $statusId): RequiredStatusModel
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPipelineId(): ?int
    {
        return $this->pipelineId;
    }

    /**
     * @param int|null $pipelineId
     *
     * @return RequiredStatusModel
     */
    public function setPipelineId(?int $pipelineId): RequiredStatusModel
    {
        $this->pipelineId = $pipelineId;

        return $this;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        return [
            'status_id' => $this->getStatusId(),
            'pipeline_id' => $this->getPipelineId(),
        ];
    }
}
