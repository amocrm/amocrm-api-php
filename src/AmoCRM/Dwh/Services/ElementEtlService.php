<?php

namespace AmoCRM\Dwh\Services;

use AmoCRM\Dwh\Models\ElementDwhModel;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\CatalogElementsFilter;

class ElementEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0, 'attributes' => 0];

        $filter = null;
        if (!empty($options['limit'])) {
            $filter = new CatalogElementsFilter();
            $filter->setLimit($options['limit']);
        }

        $page = 1;
        do {
            try {
                if ($filter && method_exists($filter, 'setPage')) {
                    $filter->setPage($page);
                }
                $collection = $this->apiClient->catalogElements()->get($filter);
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
                $collection = $this->apiClient->catalogElements()->nextPage($collection);
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

        $dwh = new ElementDwhModel();
        $dwh->setAccountId($this->accountId);
        $dwh->setElementsId($entityId);

        $this->mapFields($dwh, $model);

        $this->db->upsert(
            'amocrm_elements',
            $dwh->toInsertArray(),
            ['account_id', 'elements_id']
        );

        $customFields = $model->getCustomFieldsValues();
        if ($customFields && !$customFields->isEmpty()) {
            $attrs = $this->extractAttributes($customFields, $entityId);
            foreach ($attrs as $attr) {
                $attr['elements_id'] = $entityId;
                $this->db->insert('amocrm_elements_attributes', $attr);
                $stats['attributes']++;
            }
        }

        $stats['processed']++;
    }

    private function mapFields(ElementDwhModel $dwh, $model): void
    {
        $dwh->setCatalogId($this->toDateTimeString($model->getCatalogId()));
        $dwh->setName($this->toDateTimeString($model->getName()));
        $dwh->setArticle($this->toDateTimeString($model->getArticle()));
        $dwh->setPrice($this->toDateTimeString($model->getPrice()));
        $dwh->setQuantity($this->toDateTimeString($model->getQuantity()));
        $dwh->setExternalId($this->toDateTimeString($model->getExternalId()));
        $dwh->setIsDeleted($this->toDateTimeString($model->getIsDeleted()));
        $dwh->setCreatedUserId($this->toDateTimeString($model->getCreatedUserId()));
        $dwh->setModifiedUserId($this->toDateTimeString($model->getModifiedUserId()));
        $dwh->setCreatedAt($this->toDateTimeString($model->getCreatedAt()));
        $dwh->setUpdatedAt($this->toDateTimeString($model->getUpdatedAt()));
    }
}
