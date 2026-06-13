<?php

declare(strict_types=1);

namespace Analytics\ETL;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\CustomersOperationsCollection;
use AmoCRM\Collections\BaseApiCollection;
use Analytics\Repository\CustomerLTVRepo;
use Analytics\Logger\AnalyticsLogger;
use Carbon\Carbon;

/**
 * Exporter for customers from amoCRM
 */
class CustomersExporter extends BaseExporter
{
    private const ENTITY_NAME = 'customers';
    private CustomerLTVRepo $customerLTVRepo;

    public function __construct(
        AmoCRMApiClient $client,
        CustomerLTVRepo $customerLTVRepo,
        AnalyticsLogger $logger,
        int $batchSize = 100,
        int $maxRetries = 3
    ) {
        parent::__construct($client, $customerLTVRepo, $logger, $batchSize, $maxRetries);
        $this->customerLTVRepo = $customerLTVRepo;
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
        return $this->client->customers();
    }

    /**
     * @inheritDoc
     */
    protected function fetchPage(int $page): ?BaseApiCollection
    {
        return $this->executeWithRetry(function () use ($page) {
            return $this->client->customers()->get(
                null,
                $this->buildPaginationFilter($page)
            );
        });
    }

    /**
     * @inheritDoc
     */
    protected function fetchPageWithFilter(int $page, \DateTimeInterface $since): ?BaseApiCollection
    {
        return $this->executeWithRetry(function () use ($page, $since) {
            return $this->client->customers()->get(
                null,
                array_merge(
                    $this->buildIncrementalFilter($page, $since),
                    ['with' => 'transactions']
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

        /** @var \AmoCRM\Models\CustomerModel $customer */
        foreach ($collection as $customer) {
            $this->processCustomer($customer);
            $count++;
        }

        return $count;
    }

    /**
     * Process single customer
     */
    private function processCustomer(\AmoCRM\Models\CustomerModel $customer): void
    {
        $customerId = $customer->getId() ?? 0;

        if ($customerId === 0) {
            return;
        }

        // Get or create LTV record
        $ltv = $this->customerLTVRepo->findByCustomerId($customerId);

        if ($ltv === null) {
            $ltv = new \Analytics\Models\CustomerLTV();
            $ltv->setCustomerId($customerId);
        }

        // Update from customer model
        if ($customer->getCreatedAt()) {
            $ltv->setFirstPurchaseAt(Carbon::createFromTimestamp($customer->getCreatedAt()));
        }

        if ($customer->getLastActivityAt()) {
            $ltv->setLastPurchaseAt(Carbon::createFromTimestamp($customer->getLastActivityAt()));
        }

        // Calculate LTV from transactions
        $ltv = $this->customerLTVRepo->calculateLTV($customerId);
    }
}
