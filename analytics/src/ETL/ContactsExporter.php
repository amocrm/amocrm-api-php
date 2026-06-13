<?php

declare(strict_types=1);

namespace Analytics\ETL;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\BaseApiCollection;
use Analytics\Repository\ContactsCacheRepo;
use Analytics\Logger\AnalyticsLogger;
use Analytics\ETL\CustomFieldExtractor;
use Carbon\Carbon;

/**
 * Exporter for contacts from amoCRM
 */
class ContactsExporter extends BaseExporter
{
    private const ENTITY_NAME = 'contacts';
    private ContactsCacheRepo $contactsCacheRepo;
    private CustomFieldExtractor $customFieldExtractor;

    public function __construct(
        AmoCRMApiClient $client,
        ContactsCacheRepo $contactsCacheRepo,
        AnalyticsLogger $logger,
        CustomFieldExtractor $customFieldExtractor,
        int $batchSize = 100,
        int $maxRetries = 3
    ) {
        parent::__construct($client, $contactsCacheRepo, $logger, $batchSize, $maxRetries);
        $this->contactsCacheRepo = $contactsCacheRepo;
        $this->customFieldExtractor = $customFieldExtractor;
    }

    /**
     * @inheritDoc
     */
    protected function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    /**
     * @inheritDoc
     */
    protected function getService(): object
    {
        return $this->client->contacts();
    }

    /**
     * @inheritDoc
     */
    protected function fetchPage(int $page): ?BaseApiCollection
    {
        return $this->executeWithRetry(function () use ($page) {
            return $this->client->contacts()->get(
                null,
                array_merge(
                    $this->buildPaginationFilter($page),
                    ['with' => 'tags,company']
                )
            );
        });
    }

    /**
     * @inheritDoc
     */
    protected function fetchPageWithFilter(int $page, \DateTimeInterface $since): ?BaseApiCollection
    {
        return $this->executeWithRetry(function () use ($page, $since) {
            return $this->client->contacts()->get(
                null,
                array_merge(
                    $this->buildIncrementalFilter($page, $since),
                    ['with' => 'tags,company']
                )
            );
        });
    }

    /**
     * @inheritDoc
     */
    protected function processCollection(BaseApiCollection $collection): int
    {
        $count = 0;

        /** @var \AmoCRM\Models\ContactModel $contact */
        foreach ($collection as $contact) {
            $this->processContact($contact);
            $count++;
        }

        return $count;
    }

    /**
     * Process single contact
     */
    private function processContact(\AmoCRM\Models\ContactModel $contact): void
    {
        $contactId = $contact->getId() ?? 0;

        if ($contactId === 0) {
            return;
        }

        $data = [
            'contact_id' => $contactId,
            'name' => $contact->getName(),
            'first_name' => $contact->getFirstName(),
            'last_name' => $contact->getLastName(),
            'created_at' => $contact->getCreatedAt() 
                ? Carbon::createFromTimestamp($contact->getCreatedAt())->toDateTimeString()
                : null,
            'updated_at' => $contact->getUpdatedAt()
                ? Carbon::createFromTimestamp($contact->getUpdatedAt())->toDateTimeString()
                : null,
        ];

        // Extract custom fields
        $customFields = $this->customFieldExtractor->extractFromLeadModel($contact);

        // Extract email
        $cfValues = $contact->getCustomFieldsValues();
        if ($cfValues !== null) {
            foreach ($cfValues as $cf) {
                $fieldCode = $cf->getFieldCode() ?? '';
                if (str_contains(strtolower($fieldCode), 'email')) {
                    $values = $cf->getValues();
                    if (!empty($values)) {
                        $data['email'] = $values[0]->getValue();
                    }
                }
                if (str_contains(strtolower($fieldCode), 'phone')) {
                    $values = $cf->getValues();
                    if (!empty($values)) {
                        $data['phone'] = $values[0]->getValue();
                    }
                }
            }
        }

        $data['custom_fields'] = $customFields;
        $data['synced_at'] = Carbon::now()->toDateTimeString();

        $this->contactsCacheRepo->upsert($data);
    }
}
