<?php

namespace AmoCRM\Dwh\Services\Facts;

use AmoCRM\Dwh\Services\BaseEtlService;
use AmoCRM\Dwh\Models\CustomerFactDwhModel;

class CustomerFactEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];
        $rows = $this->db->fetchAll(
            "SELECT * FROM `amocrm_customers` WHERE account_id = :account_id",
            ['account_id' => $this->accountId]
        );
        foreach ($rows as $row) {
            $fact = new CustomerFactDwhModel();
            $fact->setAccountId($this->accountId);
            $this->mapRow($fact, $row);
            $this->db->upsert(
                'amocrm_customers_facts',
                $fact->toInsertArray(),
                ['account_id']
            );
            $stats['processed']++;
        }
        return $stats;
    }

    private function mapRow(CustomerFactDwhModel $fact, array $row): void
    {
        $fact->setCustomerId($row['customer_id'] ?? null);
        $fact->setContactId($row['contact_id'] ?? null);
        $fact->setCompanyId($row['company_id'] ?? null);
        $fact->setPeriodicityId($row['periodicity_id'] ?? null);
        $fact->setResponsibleUserId($row['responsible_user_id'] ?? null);
        $fact->setNextDate($row['next_date'] ?? null);
        $fact->setNextPrice($row['next_price'] ?? null);
        $fact->setPurchases($row['purchases'] ?? null);
        $fact->setAverageCheck($row['average_check'] ?? null);
        $fact->setLtv($row['ltv'] ?? null);
    }
}
