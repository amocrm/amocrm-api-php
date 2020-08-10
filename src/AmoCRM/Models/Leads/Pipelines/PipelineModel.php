<?php

namespace AmoCRM\Models\Leads\Pipelines;

use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\Leads\Pipelines\Statuses\StatusesCollection;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Leads\Pipelines\Statuses\StatusModel;
use AmoCRM\Models\Traits\RequestIdTrait;
use Illuminate\Contracts\Support\Arrayable;

class PipelineModel extends BaseApiModel implements Arrayable, HasIdInterface
{
    use RequestIdTrait;

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
    protected $isMain;

    /**
     * @var bool|null
     */
    protected $isUnsortedOn;

    /**
     * @var bool|null
     */
    protected $isArchive;

    /**
     * @var StatusesCollection
     */
    protected $statuses;

    /**
     * @param array $pipeline
     *
     * @return self
     */
    public static function fromArray(array $pipeline): self
    {
        $model = new self();

        $model->setId($pipeline['id']);
        $model->setName($pipeline['name']);
        $model->setSort($pipeline['sort']);
        $model->setAccountId($pipeline['account_id']);
        $model->setIsMain($pipeline['is_main']);
        $model->setIsUnsortedOn($pipeline['is_unsorted_on']);
        $model->setStatusesFromArray($pipeline[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::LEADS_STATUSES]);
        $model->setIsArchive($pipeline['is_archive']);

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
            'is_main' => $this->getIsMain(),
            'is_unsorted_on' => $this->getIsUnsortedOn(),
            'is_archive' => $this->getIsArchive(),
            'statuses' => $this->getStatuses()->toArray(),
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
     * @return PipelineModel
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
     * @return PipelineModel
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
     * @return StatusesCollection
     */
    public function getStatuses(): StatusesCollection
    {
        return $this->statuses;
    }

    /**
     * @param StatusesCollection $statuses
     * @return PipelineModel
     */
    public function setStatuses(StatusesCollection $statuses): PipelineModel
    {
        $this->statuses = $statuses;

        return $this;
    }

    /**
     * @param array $statuses
     * @return PipelineModel
     */
    public function setStatusesFromArray(array $statuses): PipelineModel
    {
        $statusesCollection = new StatusesCollection();
        if (!empty($statuses)) {
            $statusesCollection = StatusesCollection::fromArray($statuses);
        }

        return $this->setStatuses($statusesCollection);
    }

    /**
     * @param StatusModel $status
     * @return PipelineModel
     */
    public function addStatus(StatusModel $status): PipelineModel
    {
        $this->statuses->add($status);

        return $this;
    }

    /**
     * @param int|null $sort
     *
     * @return PipelineModel
     */
    public function setSort(?int $sort): PipelineModel
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
     * @return PipelineModel
     */
    public function setAccountId(?int $accountId): PipelineModel
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsMain(): ?bool
    {
        return $this->isMain;
    }

    /**
     * @param bool|null $isMain
     *
     * @return PipelineModel
     */
    public function setIsMain(?bool $isMain): PipelineModel
    {
        $this->isMain = $isMain;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsUnsortedOn(): ?bool
    {
        return $this->isUnsortedOn;
    }

    /**
     * @param bool|null $isUnsortedOn
     *
     * @return PipelineModel
     */
    public function setIsUnsortedOn(?bool $isUnsortedOn): PipelineModel
    {
        $this->isUnsortedOn = $isUnsortedOn;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsArchive(): ?bool
    {
        return $this->isArchive;
    }

    /**
     * @param bool|null $isArchive
     *
     * @return PipelineModel
     */
    public function setIsArchive(?bool $isArchive): PipelineModel
    {
        $this->isArchive = $isArchive;

        return $this;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = [];

        if (!is_null($this->getName())) {
            $result['name'] = $this->getName();
        }

        if (!is_null($this->getSort())) {
            $result['sort'] = $this->getSort();
        } elseif (empty($this->getId())) {
            $result['sort'] = 100;
        }

        if (!is_null($this->getIsMain())) {
            $result['is_main'] = $this->getIsMain();
        }

        if (!is_null($this->getIsUnsortedOn())) {
            $result['is_unsorted_on'] = $this->getIsUnsortedOn();
        } elseif (empty($this->getId())) {
            $result['is_unsorted_on'] = true;
        }

        //Статусы можно передать только при создании воронки
        if (empty($this->getId()) && !$this->getStatuses()->isEmpty()) {
            $result[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::LEADS_STATUSES] = $this->getStatuses()->toApi();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }
}
