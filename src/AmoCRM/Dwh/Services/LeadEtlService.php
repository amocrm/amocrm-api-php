<?php

namespace AmoCRM\Dwh\Services;

use AmoCRM\Dwh\Models\LeadDwhModel;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\LeadsFilter;

class LeadEtlService extends BaseEtlService
{
    public function sync(array $options = []): array
    {
        $stats = ['processed' => 0, 'attributes' => 0, 'tags' => 0, 'notes' => 0];

        $filter = null;
        if (!empty($options['limit'])) {
            $filter = new LeadsFilter();
            $filter->setLimit($options['limit']);
        }

        $page = 1;
        do {
            try {
                if ($filter && method_exists($filter, 'setPage')) {
                    $filter->setPage($page);
                }
                $collection = $this->apiClient->leads()->get($filter);
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
                $collection = $this->apiClient->leads()->nextPage($collection);
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

        $dwh = new LeadDwhModel();
        $dwh->setAccountId($this->accountId);
        $dwh->setLeadsId($entityId);

        $this->mapFields($dwh, $model);

        $this->db->upsert(
            'amocrm_leads',
            $dwh->toInsertArray(),
            ['account_id', 'leads_id']
        );

        $customFields = $model->getCustomFieldsValues();
        if ($customFields && !$customFields->isEmpty()) {
            $attrs = $this->extractAttributes($customFields, $entityId);
            foreach ($attrs as $attr) {
                $attr['leads_id'] = $entityId;
                $this->db->insert('amocrm_leads_attributes', $attr);
                $stats['attributes']++;
            }
        }

        if (method_exists($model, 'getTags') && $model->getTags() && !$model->getTags()->isEmpty()) {
            $tags = $this->extractTags($model->getTags(), $entityId);
            foreach ($tags as $tag) {
                $tag['leads_id'] = $entityId;
                $this->db->insert('amocrm_leads_tags', $tag);
                $stats['tags']++;
            }
        }

        $stats['processed']++;
    }

    private function mapFields(LeadDwhModel $dwh, $model): void
    {
        $dwh->setName($this->toDateTimeString($model->getName()));
        $dwh->setPrice($this->toDateTimeString($model->getPrice()));
        $dwh->setPipelineId($this->toDateTimeString($model->getPipelineId()));
        $dwh->setStatusId($this->toDateTimeString($model->getStatusId()));
        $dwh->setLossReasonId($this->toDateTimeString($model->getLossReasonId()));
        $dwh->setResponsibleUserId($this->toDateTimeString($model->getResponsibleUserId()));
        $dwh->setGroupId($this->toDateTimeString($model->getGroupId()));
        $dwh->setCreatedUserId($this->toDateTimeString($model->getCreatedUserId()));
        $dwh->setModifiedUserId($this->toDateTimeString($model->getModifiedUserId()));
        $dwh->setCompanyId($this->toDateTimeString($model->getCompanyId()));
        $dwh->setContactId($this->toDateTimeString($model->getContactId()));
        $dwh->setClosedUserId($this->toDateTimeString($model->getClosedUserId()));
        $dwh->setClosedAt($this->toDateTimeString($model->getClosedAt()));
        $dwh->setClosestTaskAt($this->toDateTimeString($model->getClosestTaskAt()));
        $dwh->setScore($this->toDateTimeString($model->getScore()));
        $dwh->setIsPriceModifiedByRobot($this->toDateTimeString($model->getIsPriceModifiedByRobot()));
        $dwh->setIsDeleted($this->toDateTimeString($model->getIsDeleted()));
        $dwh->setSourceExternalId($this->toDateTimeString($model->getSourceExternalId()));
        $dwh->setVisitorUid($this->toDateTimeString($model->getVisitorUid()));
        $dwh->setCreatedAt($this->toDateTimeString($model->getCreatedAt()));
        $dwh->setUpdatedAt($this->toDateTimeString($model->getUpdatedAt()));
    }
}
