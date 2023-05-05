<?php

namespace AmoCRM\Models\Leads\Pipelines\Statuses;

use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Contracts\Support\Arrayable;

class StatusDescriptionModel extends BaseApiModel implements Arrayable, HasIdInterface
{
    public const LEVEL_NEWBIE = 'newbie';
    public const LEVEL_CANDIDATE = 'candidate';
    public const LEVEL_MASTER = 'master';

    public const LEVELS = [
        self::LEVEL_NEWBIE,
        self::LEVEL_CANDIDATE,
        self::LEVEL_MASTER
    ];

    /**
     * @var int|null
     */
    protected $id = null;

    /**
     * @var int|null
     */
    protected $accountId = null;

    /**
     * @var string|null
     */
    protected $createdAt = null;

    /**
     * @var string|null
     */
    protected $updatedAt = null;

    /**
     * @var int|null
     */
    protected $createdBy = null;

    /**
     * @var int|null
     */
    protected $updatedBy = null;

    /**
     * @var int|null
     */
    protected $pipelineId = null;

    /**
     * @var int|null
     */
    protected $statusId = null;

    /**
     * @var string|null
     */
    protected $level = null;

    /**
     * @var string|null
     */
    protected $description = null;

    /**
     * @param array $status
     *
     * @return self
     */
    public static function fromArray(array $description): self
    {
        $model = new self();

        $model->setId($description['id']);
        $model->setAccountId($description['account_id']);
        $model->setCreatedAt($description['created_at']);
        $model->setUpdatedAt($description['updated_at']);
        $model->setCreatedBy($description['created_by']);
        $model->setUpdatedBy($description['updated_by']);
        $model->setPipelineId($description['pipeline_id']);
        $model->setStatusId($description['status_id']);
        $model->setLevel($description['level']);
        $model->setDescription($description['description']);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'account_id' => $this->getAccountId(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'created_by' => $this->getCreatedBy(),
            'updated_by' => $this->getUpdatedBy(),
            'pipeline_id' => $this->getPipelineId(),
            'status_id' => $this->getStatusId(),
            'level' => $this->getLevel(),
            'description' => $this->getDescription()
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
     * @param int|null $id
     *
     * @return StatusModel
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

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
     *
     * @return StatusDescriptionModel
     */
    public function setAccountId(?int $accountId): StatusDescriptionModel
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLevel(): ?string
    {
        return $this->level;
    }

    /**
     * @param string|null $level
     *
     * @return StatusDescriptionModel
     */
    public function setLevel(?string $level): StatusDescriptionModel
    {
        $this->level = $level;

        return $this;
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
     * @return StatusDescriptionModel
     */
    public function setStatusId(?int $statusId): StatusDescriptionModel
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return StatusDescriptionModel
     */
    public function setDescription(?string $description): StatusDescriptionModel
    {
        $this->description = $description;

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
     *
     * @return StatusDescriptionModel
     */
    public function setUpdatedBy(?int $updatedBy): StatusDescriptionModel
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * @param string|null $createdAt
     *
     * @return StatusDescriptionModel
     */
    public function setCreatedAt(?string $createdAt): StatusDescriptionModel
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    /**
     * @param string|null $updatedAt
     *
     * @return StatusDescriptionModel
     */
    public function setUpdatedAt(?string $updatedAt): StatusDescriptionModel
    {
        $this->updatedAt = $updatedAt;

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
     * @return StatusDescriptionModel
     */
    public function setCreatedBy(?int $createdBy): StatusDescriptionModel
    {
        $this->createdBy = $createdBy;

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
     * @return StatusDescriptionModel
     */
    public function setPipelineId(?int $pipelineId): StatusDescriptionModel
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
        $result = [];

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        if (!is_null($this->getLevel())) {
            $result['level'] = $this->getLevel();
        }

        if (!is_null($this->getDescription())) {
            $result['description'] = $this->getDescription();
        }

        return $result;
    }
}
