<?php

namespace AmoCRM\Dwh\Services\Facts;

use AmoCRM\Dwh\Services\BaseEtlService;
use AmoCRM\Dwh\Models\SegmentFactDwhModel;

class SegmentFactEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0];
        $rows = $this->db->fetchAll(
            "SELECT * FROM `amocrm_segments` WHERE account_id = :account_id",
            ['account_id' => $this->accountId]
        );
        foreach ($rows as $row) {
            $fact = new SegmentFactDwhModel();
            $fact->setAccountId($this->accountId);
            $this->mapRow($fact, $row);
            $this->db->upsert(
                'amocrm_segments_facts',
                $fact->toInsertArray(),
                ['account_id']
            );
            $stats['processed']++;
        }
        return $stats;
    }

    private function mapRow(SegmentFactDwhModel $fact, array $row): void
    {
        $fact->setSegmentId($row['segment_id'] ?? null);
        $fact->setCreatedUserId($row['created_user_id'] ?? null);
        $fact->setModifiedUserId($row['modified_user_id'] ?? null);
        $fact->setCustomersCount($row['customers_count'] ?? null);
        $fact->setConversionRate($row['conversion_rate'] ?? null);
        $fact->setMaxDiscount($row['max_discount'] ?? null);
        $fact->setCreatedAt($row['created_at'] ?? null);
        $fact->setUpdatedAt($row['updated_at'] ?? null);
    }
}
