<?php

namespace AmoCRM\Dwh\Services;

use AmoCRM\Dwh\Models\PipelineDwhModel;
use AmoCRM\Exceptions\AmoCRMApiException;

class PipelineEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];
        try {
            $collection = $this->apiClient->pipelines()->get();
        } catch (AmoCRMApiException $e) {
            return $stats;
        }
        if ($collection === null || $collection->isEmpty()) {
            return $stats;
        }
        $this->db->deleteBy('amocrm_pipelines', ['account_id' => $this->accountId]);
        foreach ($collection as $model) {
            $dwh = new PipelineDwhModel();
            $dwh->setAccountId($this->accountId);
            $dwh->setPipelineId($model->getId());
            $dwh->setName($model->getName());
            $dwh->setSort($model->getSort());
            $dwh->setIsMain($model->getIsMain() ? 1 : 0);
            $dwh->setIsUnsortedOn($model->getIsUnsortedOn() ? 1 : 0);
            $dwh->setIsArchive($model->getIsArchive() ? 1 : 0);
            $this->db->upsert('amocrm_pipelines', $dwh->toInsertArray(), ['account_id', 'pipeline_id']);
            $stats['processed']++;
        }
        return $stats;
    }
}
