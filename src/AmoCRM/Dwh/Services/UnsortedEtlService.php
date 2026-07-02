<?php

namespace AmoCRM\Dwh\Services;

use AmoCRM\Dwh\Models\UnsortedDwhModel;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\UnsortedFilter;

class UnsortedEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];

        $filter = null;
        if (!empty($options['limit'])) {
            $filter = new UnsortedFilter();
            $filter->setLimit($options['limit']);
        }

        $page = 1;
        do {
            try {
                if ($filter && method_exists($filter, 'setPage')) {
                    $filter->setPage($page);
                }
                $collection = $this->apiClient->unsorted()->get($filter);
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
                $collection = $this->apiClient->unsorted()->nextPage($collection);
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

        $dwh = new UnsortedDwhModel();
        $dwh->setAccountId($this->accountId);
        $dwh->setUnsortedId($entityId);

        $this->mapFields($dwh, $model);

        $this->db->upsert(
            'amocrm_unsorted',
            $dwh->toInsertArray(),
            ['account_id', 'unsorted_id']
        );

        $stats['processed']++;
    }

    private function mapFields(UnsortedDwhModel $dwh, $model): void
    {
        $dwh->setUid($this->toDateTimeString($model->getUid()));
        $dwh->setSourceUid($this->toDateTimeString($model->getSourceUid()));
        $dwh->setSourceName($this->toDateTimeString($model->getSourceName()));
        $dwh->setCategory($this->toDateTimeString($model->getCategory()));
        $dwh->setPipelineId($this->toDateTimeString($model->getPipelineId()));
        $dwh->setStatusId($this->toDateTimeString($model->getStatusId()));
        $dwh->setFormId($this->toDateTimeString($model->getFormId()));
        $dwh->setFormName($this->toDateTimeString($model->getFormName()));
        $dwh->setFormPage($this->toDateTimeString($model->getFormPage()));
        $dwh->setFormSentAt($this->toDateTimeString($model->getFormSentAt()));
        $dwh->setResponsibleUserId($this->toDateTimeString($model->getResponsibleUserId()));
        $dwh->setGroupId($this->toDateTimeString($model->getGroupId()));
        $dwh->setLeadId($this->toDateTimeString($model->getLeadId()));
        $dwh->setContactId($this->toDateTimeString($model->getContactId()));
        $dwh->setCompanyId($this->toDateTimeString($model->getCompanyId()));
        $dwh->setIsProcessed($this->toDateTimeString($model->getIsProcessed()));
        $dwh->setCreatedAt($this->toDateTimeString($model->getCreatedAt()));
        $dwh->setUpdatedAt($this->toDateTimeString($model->getUpdatedAt()));
    }
}
