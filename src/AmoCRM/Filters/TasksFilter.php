<?php

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasOrderInterface;
use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\ArrayOrNumericFilterTrait;
use AmoCRM\Filters\Traits\OrderTrait;
use AmoCRM\Filters\Traits\PagesFilterTrait;
use AmoCRM\Filters\Traits\IntOrIntRangeFilterTrait;

class TasksFilter extends BaseEntityFilter implements HasPagesInterface, HasOrderInterface
{
    use OrderTrait;
    use PagesFilterTrait;
    use ArrayOrNumericFilterTrait;
    use IntOrIntRangeFilterTrait;

    /**
     * @var array|int|null
     */
    private $ids = null;

    /**
     * @var null|array
     */
    private $createdBy = null;

    /**
     * @var int|array|null
     */
    private $responsibleUserId = null;

    /**
     * @var null|array|int
     */
    private $updatedAt = null;

    /**
     * @var bool|null
     */
    private $isCompleted;

    /**
     * @var int|null
     */
    private $taskTypeId;

    /**
     * @var string|null
     */
    private $entityType;

    /**
     * @var int|array|null
     */
    private $entityIds;

    /**
     * @var array|null
     */
    private $leadStatuses;

    /**
     * @return array|int|null
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @param array|int|null $ids
     *
     * @return TasksFilter
     */
    public function setIds($ids)
    {
        $this->ids = $this->parseArrayOrNumberFilter($ids);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param array|int|null $createdBy
     *
     * @return TasksFilter
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $this->parseArrayOrNumberFilter($createdBy);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getResponsibleUserId()
    {
        return $this->responsibleUserId;
    }

    /**
     * @param array|int|null $responsibleUserId
     *
     * @return TasksFilter
     */
    public function setResponsibleUserId($responsibleUserId)
    {
        $this->responsibleUserId = $this->parseArrayOrNumberFilter($responsibleUserId);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param BaseRangeFilter|int|null $updatedAt
     *
     * @return TasksFilter
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $this->parseIntOrIntRangeFilter($updatedAt);

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsCompleted(): ?bool
    {
        return $this->isCompleted;
    }

    /**
     * @param bool|null $isCompleted
     *
     * @return TasksFilter
     */
    public function setIsCompleted(?bool $isCompleted): TasksFilter
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTaskTypeId(): ?int
    {
        return $this->taskTypeId;
    }

    /**
     * @param int|null $taskTypeId
     *
     * @return TasksFilter
     */
    public function setTaskTypeId(?int $taskTypeId): TasksFilter
    {
        $this->taskTypeId = $taskTypeId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEntityType(): ?string
    {
        return $this->entityType;
    }

    /**
     * @param string|null $entityType
     *
     * @return TasksFilter
     */
    public function setEntityType(?string $entityType): TasksFilter
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getEntityIds()
    {
        return $this->entityIds;
    }

    /**
     * @param array|int|null $entityIds
     *
     * @return TasksFilter
     */
    public function setEntityIds($entityIds)
    {
        $this->entityIds = $entityIds;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getLeadStatuses(): ?array
    {
        return $this->leadStatuses;
    }

    /**
     * @param array|null $leadStatuses
     *
     * @return TasksFilter
     */
    public function setLeadStatuses(?array $leadStatuses): TasksFilter
    {
        $statusesFilter = [];

        foreach ($leadStatuses as $status) {
            $statusesFilter[] = [
                'status_id' => $status['status_id'] ?? null,
                'pipeline_id' => $status['pipeline_id'] ?? null,
            ];
        }

        $this->leadStatuses = $statusesFilter;

        return $this;
    }

    /**
     * @return array
     */
    public function buildFilter(): array
    {
        $filter = [];

        if (!is_null($this->getIds())) {
            $filter['filter']['id'] = $this->getIds();
        }

        if (!is_null($this->getCreatedBy())) {
            $filter['filter']['created_by'] = $this->getCreatedBy();
        }

        if (!is_null($this->getIsCompleted())) {
            $filter['filter']['is_completed'] = $this->getIsCompleted();
        }

        //todo удалить после перехода всех пользователей на новую версию
        if (!is_null($this->getTaskTypeId())) {
            $filter['filter']['task_type'] = $this->getTaskTypeId();
        }

        if (!is_null($this->getTaskTypeId())) {
            $filter['filter']['task_type_id'] = $this->getTaskTypeId();
        }

        if (!is_null($this->getResponsibleUserId())) {
            $filter['filter']['responsible_user_id'] = $this->getResponsibleUserId();
        }

        if (!is_null($this->getEntityType())) {
            $filter['filter']['entity_type'] = $this->getEntityType();

            if (!is_null($this->getEntityIds())) {
                $filter['filter']['entity_id'] = $this->getEntityIds();
            }
        }

        if (!is_null($this->getUpdatedAt())) {
            $filter['filter']['updated_at'] = $this->getUpdatedAt();
        }

        if (!is_null($this->getLeadStatuses())) {
            $filter['filter']['lead_statuses'] = $this->getLeadStatuses();
        }

        if (!is_null($this->getOrder())) {
            $filter['order'] = $this->getOrder();
        }

        $filter = $this->buildPagesFilter($filter);

        return $filter;
    }
}
