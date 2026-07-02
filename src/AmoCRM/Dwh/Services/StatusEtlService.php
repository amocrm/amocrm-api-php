<?php

namespace AmoCRM\Dwh\Services;

use AmoCRM\Dwh\Models\StatusDwhModel;
use AmoCRM\Exceptions\AmoCRMApiException;

class StatusEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];
        try {
            $pipelines = $this->apiClient->pipelines()->get();
        } catch (AmoCRMApiException $e) {
            return $stats;
        }
        if ($pipelines === null || $pipelines->isEmpty()) {
            return $stats;
        }
        $this->db->deleteBy('amocrm_statuses', ['account_id' => $this->accountId]);
        foreach ($pipelines as $pipeline) {
            $pipelineId = $pipeline->getId();
            try {
                $statuses = $this->apiClient->statuses($pipelineId)->get();
            } catch (AmoCRMApiException $e) {
                continue;
            }
            if ($statuses === null || $statuses->isEmpty()) {
                continue;
            }
            foreach ($statuses as $model) {
                $dwh = new StatusDwhModel();
                $dwh->setAccountId($this->accountId);
                $dwh->setStatusId($model->getId());
                $dwh->setPipelineId($pipelineId);
                $dwh->setName($model->getName());
                $dwh->setSort($model->getSort());
                $dwh->setColor($model->getColor());
                $dwh->setType($model->getType());
                $dwh->setEditable($model->getEditable() ? 1 : 0);
                $this->db->upsert('amocrm_statuses', $dwh->toInsertArray(), ['account_id', 'status_id']);
                $stats['processed']++;
            }
        }
        return $stats;
    }
}
