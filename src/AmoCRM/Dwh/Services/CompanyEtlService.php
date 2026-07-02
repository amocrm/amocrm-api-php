<?php

namespace AmoCRM\Dwh\Services;

use AmoCRM\Dwh\Models\CompanyDwhModel;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\CompaniesFilter;

class CompanyEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0, 'attributes' => 0, 'tags' => 0, 'notes' => 0];

        $filter = null;
        if (!empty($options['limit'])) {
            $filter = new CompaniesFilter();
            $filter->setLimit($options['limit']);
        }

        $page = 1;
        do {
            try {
                if ($filter && method_exists($filter, 'setPage')) {
                    $filter->setPage($page);
                }
                $collection = $this->apiClient->companies()->get($filter);
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
                $collection = $this->apiClient->companies()->nextPage($collection);
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

        $dwh = new CompanyDwhModel();
        $dwh->setAccountId($this->accountId);
        $dwh->setCompaniesId($entityId);

        $this->mapFields($dwh, $model);

        $this->db->upsert(
            'amocrm_companies',
            $dwh->toInsertArray(),
            ['account_id', 'companies_id']
        );

        $customFields = $model->getCustomFieldsValues();
        if ($customFields && !$customFields->isEmpty()) {
            $attrs = $this->extractAttributes($customFields, $entityId);
            foreach ($attrs as $attr) {
                $attr['companies_id'] = $entityId;
                $this->db->insert('amocrm_companies_attributes', $attr);
                $stats['attributes']++;
            }
        }

        if (method_exists($model, 'getTags') && $model->getTags() && !$model->getTags()->isEmpty()) {
            $tags = $this->extractTags($model->getTags(), $entityId);
            foreach ($tags as $tag) {
                $tag['companies_id'] = $entityId;
                $this->db->insert('amocrm_companies_tags', $tag);
                $stats['tags']++;
            }
        }

        $stats['processed']++;
    }

    private function mapFields(CompanyDwhModel $dwh, $model): void
    {
        $dwh->setName($this->toDateTimeString($model->getName()));
        $dwh->setResponsibleUserId($this->toDateTimeString($model->getResponsibleUserId()));
        $dwh->setGroupId($this->toDateTimeString($model->getGroupId()));
        $dwh->setCreatedUserId($this->toDateTimeString($model->getCreatedUserId()));
        $dwh->setModifiedUserId($this->toDateTimeString($model->getModifiedUserId()));
        $dwh->setClosestTaskAt($this->toDateTimeString($model->getClosestTaskAt()));
        $dwh->setScore($this->toDateTimeString($model->getScore()));
        $dwh->setIsDeleted($this->toDateTimeString($model->getIsDeleted()));
        $dwh->setCreatedAt($this->toDateTimeString($model->getCreatedAt()));
        $dwh->setUpdatedAt($this->toDateTimeString($model->getUpdatedAt()));
    }
}
