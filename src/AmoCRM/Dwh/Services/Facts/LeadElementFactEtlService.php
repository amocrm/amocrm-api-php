<?php

namespace AmoCRM\Dwh\Services\Facts;

use AmoCRM\Dwh\Services\BaseEtlService;
use AmoCRM\Dwh\Models\LeadElementFactDwhModel;

class LeadElementFactEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];
        $rows = $this->db->fetchAll(
            "SELECT * FROM amocrm_leads WHERE account_id = :account_id",
            ['account_id' => $this->accountId]
        );
        foreach ($rows as $row) {
            $fact = new LeadElementFactDwhModel();
            $fact->setAccountId($this->accountId);
            $fact->setLeadId($row['lead_id'] ?? null);
            $stats['processed']++;
            $this->db->upsert('amocrm_leads_elements_facts', $fact->toInsertArray(), ['account_id']);
        }
        return $stats;
    }
}
