<?php

namespace AmoCRM\Dwh\Services\Facts;

use AmoCRM\Dwh\Services\BaseEtlService;
use AmoCRM\Dwh\Models\TransactionElementFactDwhModel;

class TransactionElementFactEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];
        $rows = $this->db->fetchAll(
            "SELECT * FROM amocrm_transactions WHERE account_id = :account_id",
            ['account_id' => $this->accountId]
        );
        foreach ($rows as $row) {
            $fact = new TransactionElementFactDwhModel();
            $fact->setAccountId($this->accountId);
            $fact->setTransactionId($row['transaction_id'] ?? null);
            $stats['processed']++;
            $this->db->upsert('amocrm_transactions_elements_facts', $fact->toInsertArray(), ['account_id']);
        }
        return $stats;
    }
}
