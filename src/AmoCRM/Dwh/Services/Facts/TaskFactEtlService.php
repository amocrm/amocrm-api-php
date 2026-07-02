<?php

namespace AmoCRM\Dwh\Services\Facts;

use AmoCRM\Dwh\Services\BaseEtlService;
use AmoCRM\Dwh\Models\TaskFactDwhModel;

class TaskFactEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];
        $rows = $this->db->fetchAll(
            "SELECT * FROM `amocrm_tasks` WHERE account_id = :account_id",
            ['account_id' => $this->accountId]
        );
        foreach ($rows as $row) {
            $fact = new TaskFactDwhModel();
            $fact->setAccountId($this->accountId);
            $this->mapRow($fact, $row);
            $this->db->upsert(
                'amocrm_tasks_facts',
                $fact->toInsertArray(),
                ['account_id']
            );
            $stats['processed']++;
        }
        return $stats;
    }

    private function mapRow(TaskFactDwhModel $fact, array $row): void
    {
        $fact->setTaskId($row['task_id'] ?? null);
        $fact->setEntityType($row['entity_type'] ?? null);
        $fact->setEntityId($row['entity_id'] ?? null);
        $fact->setResponsibleUserId($row['responsible_user_id'] ?? null);
        $fact->setDuration($row['duration'] ?? null);
        $fact->setIsCompleted($row['is_completed'] ?? 0);
    }
}
