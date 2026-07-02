<?php

namespace AmoCRM\Dwh\Services\Facts;

use AmoCRM\Dwh\Services\BaseEtlService;
use AmoCRM\Dwh\Models\CompanyFactDwhModel;

class CompanyFactEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];
        $rows = $this->db->fetchAll(
            "SELECT * FROM `amocrm_companies` WHERE account_id = :account_id",
            ['account_id' => $this->accountId]
        );
        foreach ($rows as $row) {
            $fact = new CompanyFactDwhModel();
            $fact->setAccountId($this->accountId);
            $this->mapRow($fact, $row);
            $this->db->upsert(
                'amocrm_companies_facts',
                $fact->toInsertArray(),
                ['account_id']
            );
            $stats['processed']++;
        }
        return $stats;
    }

    private function mapRow(CompanyFactDwhModel $fact, array $row): void
    {
        $fact->setCompanyId($row['company_id'] ?? null);
        $fact->setCreatedUserId($row['created_user_id'] ?? null);
        $fact->setResponsibleUserId($row['responsible_user_id'] ?? null);
        $fact->setScore($row['score'] ?? null);
    }
}
