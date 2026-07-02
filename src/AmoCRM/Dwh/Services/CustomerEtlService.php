<?php

namespace AmoCRM\Dwh\Services;

use AmoCRM\Dwh\Models\CustomerDwhModel;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\CustomersFilter;

class CustomerEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0, 'attributes' => 0, 'tags' => 0, 'notes' => 0];

        $filter = null;
        if (!empty($options['limit'])) {
            $filter = new CustomersFilter();
            $filter->setLimit($options['limit']);
        }

        $page = 1;
        do {
            try {
                if ($filter && method_exists($filter, 'setPage')) {
                    $filter->setPage($page);
                }
                $collection = $this->apiClient->customers()->get($filter);
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
                $collection = $this->apiClient->customers()->nextPage($collection);
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

        $dwh = new CustomerDwhModel();
        $dwh->setAccountId($this->accountId);
        $dwh->setCustomersId($entityId);

        $this->mapFields($dwh, $model);

        $this->db->upsert(
            'amocrm_customers',
            $dwh->toInsertArray(),
            ['account_id', 'customers_id']
        );

        $customFields = $model->getCustomFieldsValues();
        if ($customFields && !$customFields->isEmpty()) {
            $attrs = $this->extractAttributes($customFields, $entityId);
            foreach ($attrs as $attr) {
                $attr['customers_id'] = $entityId;
                $this->db->insert('amocrm_customers_attributes', $attr);
                $stats['attributes']++;
            }
        }

        if (method_exists($model, 'getTags') && $model->getTags() && !$model->getTags()->isEmpty()) {
            $tags = $this->extractTags($model->getTags(), $entityId);
            foreach ($tags as $tag) {
                $tag['customers_id'] = $entityId;
                $this->db->insert('amocrm_customers_tags', $tag);
                $stats['tags']++;
            }
        }

        $stats['processed']++;
    }

    private function mapFields(CustomerDwhModel $dwh, $model): void
    {
        $dwh->setName($this->toDateTimeString($model->getName()));
        $dwh->setContactId($this->toDateTimeString($model->getContactId()));
        $dwh->setCompanyId($this->toDateTimeString($model->getCompanyId()));
        $dwh->setResponsibleUserId($this->toDateTimeString($model->getResponsibleUserId()));
        $dwh->setStatusId($this->toDateTimeString($model->getStatusId()));
        $dwh->setPeriodicityId($this->toDateTimeString($model->getPeriodicityId()));
        $dwh->setPeriodId($this->toDateTimeString($model->getPeriodId()));
        $dwh->setNextPrice($this->toDateTimeString($model->getNextPrice()));
        $dwh->setLtv($this->toDateTimeString($model->getLtv()));
        $dwh->setPurchases($this->toDateTimeString($model->getPurchases()));
        $dwh->setAverageCheck($this->toDateTimeString($model->getAverageCheck()));
        $dwh->setNextDate($this->toDateTimeString($model->getNextDate()));
        $dwh->setIsDeleted($this->toDateTimeString($model->getIsDeleted()));
        $dwh->setCreatedUserId($this->toDateTimeString($model->getCreatedUserId()));
        $dwh->setModifiedUserId($this->toDateTimeString($model->getModifiedUserId()));
        $dwh->setCreatedAt($this->toDateTimeString($model->getCreatedAt()));
        $dwh->setUpdatedAt($this->toDateTimeString($model->getUpdatedAt()));
    }
}
