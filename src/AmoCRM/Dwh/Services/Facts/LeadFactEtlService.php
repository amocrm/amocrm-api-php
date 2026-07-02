<?php

namespace AmoCRM\Dwh\Services\Facts;

use AmoCRM\Dwh\Services\BaseEtlService;
use AmoCRM\Dwh\Models\LeadFactDwhModel;

class LeadFactEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];
        $rows = $this->db->fetchAll(
            "SELECT * FROM `amocrm_leads` WHERE account_id = :account_id",
            ['account_id' => $this->accountId]
        );
        foreach ($rows as $row) {
            $fact = new LeadFactDwhModel();
            $fact->setAccountId($this->accountId);
            $this->mapRow($fact, $row);
            $this->db->upsert(
                'amocrm_leads_facts',
                $fact->toInsertArray(),
                ['account_id']
            );
            $stats['processed']++;
        }
        return $stats;
    }

    private function mapRow(LeadFactDwhModel $fact, array $row): void
    {
        $fact->setLeadId($row['lead_id'] ?? null);
        $fact->setPipelineId($row['pipeline_id'] ?? null);
        $fact->setStatusId($row['status_id'] ?? null);
        $fact->setLossReasonId($row['loss_reason_id'] ?? null);
        $fact->setResponsibleUserId($row['responsible_user_id'] ?? null);
        $fact->setCompanyId($row['company_id'] ?? null);
        $fact->setContactId($row['contact_id'] ?? null);
        $fact->setPrice($row['price'] ?? null);
        $fact->setScore($row['score'] ?? null);
        $fact->setClientId($row['visitor_uid'] ?? null);
        $fact->setTrafficId($row['source_external_id'] ?? null);
    }
}
