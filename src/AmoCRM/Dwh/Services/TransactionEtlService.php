<?php

namespace AmoCRM\Dwh\Services;

use AmoCRM\Dwh\Models\TransactionDwhModel;
use AmoCRM\Exceptions\AmoCRMApiException;

class TransactionEtlService extends BaseEtlService
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
                $collection = $this->apiClient->transactions()->get($filter);
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
                $collection = $this->apiClient->transactions()->nextPage($collection);
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

        $dwh = new TransactionDwhModel();
        $dwh->setAccountId($this->accountId);
        $dwh->setTransactionsId($entityId);

        $this->mapFields($dwh, $model);

        $this->db->upsert(
            'amocrm_transactions',
            $dwh->toInsertArray(),
            ['account_id', 'transactions_id']
        );

        $stats['processed']++;
    }

    private function mapFields(TransactionDwhModel $dwh, $model): void
    {
        $dwh->setCustomerId($this->toDateTimeString($model->getCustomerId()));
        $dwh->setContactId($this->toDateTimeString($model->getContactId()));
        $dwh->setCompanyId($this->toDateTimeString($model->getCompanyId()));
        $dwh->setCompletedAt($this->toDateTimeString($model->getCompletedAt()));
        $dwh->setPrice($this->toDateTimeString($model->getPrice()));
        $dwh->setComment($this->toDateTimeString($model->getComment()));
        $dwh->setCreatedUserId($this->toDateTimeString($model->getCreatedUserId()));
        $dwh->setModifiedUserId($this->toDateTimeString($model->getModifiedUserId()));
        $dwh->setIsDeleted($this->toDateTimeString($model->getIsDeleted()));
        $dwh->setCreatedAt($this->toDateTimeString($model->getCreatedAt()));
        $dwh->setUpdatedAt($this->toDateTimeString($model->getUpdatedAt()));
    }
}
