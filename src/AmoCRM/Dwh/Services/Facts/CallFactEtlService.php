<?php

namespace AmoCRM\Dwh\Services\Facts;

use AmoCRM\Dwh\Services\BaseEtlService;
use AmoCRM\Dwh\Models\CallFactDwhModel;

class CallFactEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];
        $rows = $this->db->fetchAll(
            "SELECT * FROM `amocrm_calls` WHERE account_id = :account_id",
            ['account_id' => $this->accountId]
        );
        foreach ($rows as $row) {
            $fact = new CallFactDwhModel();
            $fact->setAccountId($this->accountId);
            $this->mapRow($fact, $row);
            $this->db->upsert(
                'amocrm_calls_facts',
                $fact->toInsertArray(),
                ['account_id']
            );
            $stats['processed']++;
        }
        return $stats;
    }

    private function mapRow(CallFactDwhModel $fact, array $row): void
    {
        $fact->setCallId($row['call_id'] ?? null);
        $fact->setEntityType($row['entity_type'] ?? null);
        $fact->setEntityId($row['entity_id'] ?? null);
        $fact->setUserId($row['call_responsible_user_id'] ?? null);
        $fact->setDuration($row['duration'] ?? null);
        $fact->setStatus($row['status'] ?? null);
    }
}
