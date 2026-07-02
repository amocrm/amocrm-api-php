<?php

namespace AmoCRM\Dwh\Services\Facts;

use AmoCRM\Dwh\Services\BaseEtlService;
use AmoCRM\Dwh\Models\CustomerElementFactDwhModel;

class CustomerElementFactEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];
        $rows = $this->db->fetchAll(
            "SELECT * FROM amocrm_customers WHERE account_id = :account_id",
            ['account_id' => $this->accountId]
        );
        foreach ($rows as $row) {
            $fact = new CustomerElementFactDwhModel();
            $fact->setAccountId($this->accountId);
            $fact->setCustomerId($row['customer_id'] ?? null);
            $stats['processed']++;
            $this->db->upsert('amocrm_customers_elements_facts', $fact->toInsertArray(), ['account_id']);
        }
        return $stats;
    }
}
