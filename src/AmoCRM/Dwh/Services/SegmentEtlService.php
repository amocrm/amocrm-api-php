<?php

namespace AmoCRM\Dwh\Services;

use AmoCRM\Dwh\Models\SegmentDwhModel;
use AmoCRM\Exceptions\AmoCRMApiException;

class SegmentEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0, 'attributes' => 0];

        $filter = null;

        $page = 1;
        do {
            try {
                if ($filter && method_exists($filter, 'setPage')) {
                    $filter->setPage($page);
                }
                $collection = $this->apiClient->customersSegments()->get($filter);
            } catch (AmoCRMApiException $e) {
                break;
            }

            if ($collection === null || $collection->isEmpty()) {
                break;
            }

            foreach ($collection as $model) {
                $this->syncEntity($model, $stats);
            }

            try {
                $collection = $this->apiClient->customersSegments()->nextPage($collection);
            } catch (AmoCRMApiException $e) {
                break;
            }

            $page++;
        } while ($collection !== null && !$collection->isEmpty());

        return $stats;
    }

    private function syncEntity($model, array &$stats): void
    {
        $entityId = $model->getId();

        $dwh = new SegmentDwhModel();
        $dwh->setAccountId($this->accountId);
        $dwh->setSegmentsId($entityId);

        $this->mapFields($dwh, $model);

        $this->db->upsert(
            'amocrm_segments',
            $dwh->toInsertArray(),
            ['account_id', 'segments_id']
        );

        $customFields = $model->getCustomFieldsValues();
        if ($customFields && !$customFields->isEmpty()) {
            $attrs = $this->extractAttributes($customFields, $entityId);
            foreach ($attrs as $attr) {
                $attr['segments_id'] = $entityId;
                $this->db->insert('amocrm_segments_attributes', $attr);
                $stats['attributes']++;
            }
        }

        $stats['processed']++;
    }

    private function mapFields(SegmentDwhModel $dwh, $model): void
    {
        $dwh->setName($this->toDateTimeString($model->getName()));
        $dwh->setColor($this->toDateTimeString($model->getColor()));
        $dwh->setCreatedUserId($this->toDateTimeString($model->getCreatedUserId()));
        $dwh->setModifiedUserId($this->toDateTimeString($model->getModifiedUserId()));
        $dwh->setCustomersCount($this->toDateTimeString($model->getCustomersCount()));
        $dwh->setConversionRate($this->toDateTimeString($model->getConversionRate()));
        $dwh->setMaxDiscount($this->toDateTimeString($model->getMaxDiscount()));
        $dwh->setCreatedAt($this->toDateTimeString($model->getCreatedAt()));
        $dwh->setUpdatedAt($this->toDateTimeString($model->getUpdatedAt()));
    }
}
