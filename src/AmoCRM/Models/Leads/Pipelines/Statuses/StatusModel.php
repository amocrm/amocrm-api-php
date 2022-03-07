<?php

namespace AmoCRM\Models\Leads\Pipelines\Statuses;

use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Traits\RequestIdTrait;
use AmoCRM\Contracts\Support\Arrayable;

class StatusModel extends BaseApiModel implements Arrayable, HasIdInterface
{
    use RequestIdTrait;

    public const COLORS = [
        '#fffeb2','#fffd7f','#fff000','#ffeab2','#ffdc7f',
        '#ffce5a','#ffdbdb','#ffc8c8','#ff8f92','#d6eaff',
        '#c1e0ff','#98cbff','#ebffb1','#deff81','#87f2c0',
        '#f9deff','#f3beff','#ccc8f9','#eb93ff','#f2f3f4',
        '#e6e8ea',
    ];

    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var int|null
     */
    protected $sort;

    /**
     * @var int|null
     */
    protected $accountId;

    /**
     * @var bool|null
     */
    protected $isEditable;

    /**
     * @var string|null
     */
    protected $color;

    /**
     * @var int|null
     */
    protected $type;

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

        $model->setId($status['id']);
        $model->setName($status['name']);
        $model->setSort($status['sort']);
        $model->setAccountId($status['account_id']);
        $model->setIsEditable($status['is_editable']);
        $model->setPipelineId($status['pipeline_id']);
        $model->setColor($status['color']);
        $model->setType($status['type']);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'sort' => $this->getSort(),
            'account_id' => $this->getAccountId(),
            'type' => $this->getType(),
            'color' => $this->getColor(),
            'is_editable' => $this->getIsEditable(),
            'pipeline_id' => $this->getPipelineId(),
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
     * @return StatusModel
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return StatusModel
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getSort(): ?int
    {
        return $this->sort;
    }

    /**
     * @param int|null $sort
     *
     * @return StatusModel
     */
    public function setSort(?int $sort): StatusModel
    {
        $this->sort = $sort;

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
     * @return StatusModel
     */
    public function setAccountId(?int $accountId): StatusModel
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsEditable(): ?bool
    {
        return $this->isEditable;
    }

    /**
     * @param bool|null $isEditable
     *
     * @return StatusModel
     */
    public function setIsEditable(?bool $isEditable): StatusModel
    {
        $this->isEditable = $isEditable;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     *
     * @return StatusModel
     */
    public function setColor(?string $color): StatusModel
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param int|null $type
     *
     * @return StatusModel
     */
    public function setType(?int $type): StatusModel
    {
        $this->type = $type;

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
     * @return StatusModel
     */
    public function setPipelineId(?int $pipelineId): StatusModel
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

        if (!is_null($this->getId()) && !$this->getPipelineId()) {
            $result['id'] = $this->getId();
        }

        if (!is_null($this->getName())) {
            $result['name'] = $this->getName();
        }

        if (!is_null($this->getSort())) {
            $result['sort'] = $this->getSort();
        }

        if (!is_null($this->getColor())) {
            $result['color'] = $this->getColor();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }
}
