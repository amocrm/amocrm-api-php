<?php

namespace AmoCRM\Dwh\Services;

use AmoCRM\Dwh\Models\PeriodicityDwhModel;
use AmoCRM\Exceptions\AmoCRMApiException;

class PeriodicityEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];
        try {
            $collection = $this->apiClient->customersStatuses()->get();
        } catch (AmoCRMApiException $e) {
            return $stats;
        }
        if ($collection === null || $collection->isEmpty()) {
            return $stats;
        }
        $this->db->deleteBy('amocrm_periodicity', ['account_id' => $this->accountId]);
        foreach ($collection as $model) {
            $dwh = new PeriodicityDwhModel();
            $dwh->setAccountId($this->accountId);
            $dwh->setPeriodicityId($model->getId());
            $dwh->setName($model->getName());
            $dwh->setSort($model->getSort());
            $dwh->setColor($model->getColor());
            $this->db->upsert('amocrm_periodicity', $dwh->toInsertArray(), ['account_id', 'periodicity_id']);
            $stats['processed']++;
        }
        return $stats;
    }
}
