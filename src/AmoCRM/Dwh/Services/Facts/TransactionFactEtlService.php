<?php

namespace AmoCRM\Dwh\Services\Facts;

use AmoCRM\Dwh\Services\BaseEtlService;
use AmoCRM\Dwh\Models\TransactionFactDwhModel;

class TransactionFactEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];
        $rows = $this->db->fetchAll(
            "SELECT * FROM `amocrm_transactions` WHERE account_id = :account_id",
            ['account_id' => $this->accountId]
        );
        foreach ($rows as $row) {
            $fact = new TransactionFactDwhModel();
            $fact->setAccountId($this->accountId);
            $this->mapRow($fact, $row);
            $this->db->upsert(
                'amocrm_transactions_facts',
                $fact->toInsertArray(),
                ['account_id']
            );
            $stats['processed']++;
        }
        return $stats;
    }

    private function mapRow(TransactionFactDwhModel $fact, array $row): void
    {
        $fact->setTransactionId($row['transaction_id'] ?? null);
        $fact->setCustomerId($row['customer_id'] ?? null);
        $fact->setContactId($row['contact_id'] ?? null);
        $fact->setCompanyId($row['company_id'] ?? null);
        $fact->setCompletedAt($row['completed_at'] ?? null);
        $fact->setPrice($row['price'] ?? null);
    }
}
