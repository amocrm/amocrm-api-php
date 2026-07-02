<?php

namespace AmoCRM\Dwh\Services;

use AmoCRM\Dwh\Models\CallDwhModel;
use AmoCRM\Exceptions\AmoCRMApiException;

class CallEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];

        $filter = null;

        $page = 1;
        do {
            try {
                if ($filter && method_exists($filter, 'setPage')) {
                    $filter->setPage($page);
                }
                $collection = $this->apiClient->calls()->get($filter);
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
                $collection = $this->apiClient->calls()->nextPage($collection);
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

        $dwh = new CallDwhModel();
        $dwh->setAccountId($this->accountId);
        $dwh->setCallsId($entityId);

        $this->mapFields($dwh, $model);

        $this->db->upsert(
            'amocrm_calls',
            $dwh->toInsertArray(),
            ['account_id', 'calls_id']
        );

        $stats['processed']++;
    }

    private function mapFields(CallDwhModel $dwh, $model): void
    {
        $dwh->setEntityType($this->toDateTimeString($model->getEntityType()));
        $dwh->setEntityId($this->toDateTimeString($model->getEntityId()));
        $dwh->setPhone($this->toDateTimeString($model->getPhone()));
        $dwh->setDirection($this->toDateTimeString($model->getDirection()));
        $dwh->setStatus($this->toDateTimeString($model->getStatus()));
        $dwh->setResult($this->toDateTimeString($model->getResult()));
        $dwh->setDuration($this->toDateTimeString($model->getDuration()));
        $dwh->setCallResponsibleUserId($this->toDateTimeString($model->getResponsibleUserId()));
        $dwh->setSource($this->toDateTimeString($model->getSource()));
        $dwh->setNote($this->toDateTimeString($model->getNote()));
        $dwh->setCreatedAt($this->toDateTimeString($model->getCreatedAt()));
        $dwh->setUpdatedAt($this->toDateTimeString($model->getUpdatedAt()));
    }
}
