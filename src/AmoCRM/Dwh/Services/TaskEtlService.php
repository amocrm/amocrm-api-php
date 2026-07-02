<?php

namespace AmoCRM\Dwh\Services;

use AmoCRM\Dwh\Models\TaskDwhModel;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\TasksFilter;

class TaskEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];

        $filter = null;
        if (!empty($options['limit'])) {
            $filter = new TasksFilter();
            $filter->setLimit($options['limit']);
        }

        $page = 1;
        do {
            try {
                if ($filter && method_exists($filter, 'setPage')) {
                    $filter->setPage($page);
                }
                $collection = $this->apiClient->tasks()->get($filter);
            } catch (AmoCRMApiException $e) {
                break;
            }

            if ($collection === null || $collection->isEmpty()) {
                break;
            }

            foreach ($collection as $model) {
                $this->syncEntity($model, $stats);
            }

            try {
                $collection = $this->apiClient->tasks()->nextPage($collection);
            } catch (AmoCRMApiException $e) {
                break;
            }

            $page++;
        } while ($collection !== null && !$collection->isEmpty());

        return $stats;
    }

    private function syncEntity($model, array &$stats): void
    {
        $entityId = $model->getId();

        $dwh = new TaskDwhModel();
        $dwh->setAccountId($this->accountId);
        $dwh->setTasksId($entityId);

        $this->mapFields($dwh, $model);

        $this->db->upsert(
            'amocrm_tasks',
            $dwh->toInsertArray(),
            ['account_id', 'tasks_id']
        );

        $stats['processed']++;
    }

    private function mapFields(TaskDwhModel $dwh, $model): void
    {
        $dwh->setName($this->toDateTimeString($model->getName()));
        $dwh->setResponsibleUserId($this->toDateTimeString($model->getResponsibleUserId()));
        $dwh->setCreatedUserId($this->toDateTimeString($model->getCreatedUserId()));
        $dwh->setModifiedUserId($this->toDateTimeString($model->getModifiedUserId()));
        $dwh->setTaskTypeId($this->toDateTimeString($model->getTaskTypeId()));
        $dwh->setEntityType($this->toDateTimeString($model->getEntityType()));
        $dwh->setEntityId($this->toDateTimeString($model->getEntityId()));
        $dwh->setDuration($this->toDateTimeString($model->getDuration()));
        $dwh->setCompleteTillAt($this->toDateTimeString($model->getCompleteTillAt()));
        $dwh->setCompletedAt($this->toDateTimeString($model->getCompletedAt()));
        $dwh->setResult($this->toDateTimeString($model->getResult()));
        $dwh->setIsCompleted($this->toDateTimeString($model->getIsCompleted()));
        $dwh->setGroupId($this->toDateTimeString($model->getGroupId()));
        $dwh->setIsDeleted($this->toDateTimeString($model->getIsDeleted()));
        $dwh->setCreatedAt($this->toDateTimeString($model->getCreatedAt()));
        $dwh->setUpdatedAt($this->toDateTimeString($model->getUpdatedAt()));
    }
}
