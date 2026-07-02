<?php

namespace AmoCRM\Dwh;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Dwh\Services\PipelineEtlService;
use AmoCRM\Dwh\Services\StatusEtlService;
use AmoCRM\Dwh\Services\PeriodicityEtlService;
use AmoCRM\Dwh\Services\UserEtlService;
use AmoCRM\Dwh\Services\CompanyEtlService;
use AmoCRM\Dwh\Services\ContactEtlService;
use AmoCRM\Dwh\Services\LeadEtlService;
use AmoCRM\Dwh\Services\CustomerEtlService;
use AmoCRM\Dwh\Services\SegmentEtlService;
use AmoCRM\Dwh\Services\CallEtlService;
use AmoCRM\Dwh\Services\TaskEtlService;
use AmoCRM\Dwh\Services\TransactionEtlService;
use AmoCRM\Dwh\Services\ElementEtlService;
use AmoCRM\Dwh\Services\UnsortedEtlService;
use AmoCRM\Dwh\Services\Facts\CallFactEtlService;
use AmoCRM\Dwh\Services\Facts\ContactFactEtlService;
use AmoCRM\Dwh\Services\Facts\CompanyFactEtlService;
use AmoCRM\Dwh\Services\Facts\LeadFactEtlService;
use AmoCRM\Dwh\Services\Facts\SegmentFactEtlService;
use AmoCRM\Dwh\Services\Facts\CustomerFactEtlService;
use AmoCRM\Dwh\Services\Facts\TransactionFactEtlService;
use AmoCRM\Dwh\Services\Facts\TaskFactEtlService;
use AmoCRM\Dwh\Services\Facts\TransactionElementFactEtlService;
use AmoCRM\Dwh\Services\Facts\LeadElementFactEtlService;
use AmoCRM\Dwh\Services\Facts\CustomerElementFactEtlService;
use DateTime;

class DwhSyncOrchestrator
{
    private AmoCRMApiClient $apiClient;
    private DwhDbAdapter $db;
    private int $accountId;

    public function __construct(AmoCRMApiClient $apiClient, DwhDbAdapter $db, int $accountId)
    {
        $this->apiClient = $apiClient;
        $this->db = $db;
        $this->accountId = $accountId;
    }

    /**
     * Full synchronization of all data from amoCRM to DWH.
     */
    public function fullSync(array $options = []): array
    {
        $report = [];
        $entityOptions = ['limit' => $options['limit'] ?? 250];

        // Stage 1: Auxiliary tables (references)
        $report['pipelines'] = (new PipelineEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync()['processed'];
        $report['statuses'] = (new StatusEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync()['processed'];
        $report['periodicity'] = (new PeriodicityEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync()['processed'];

        // Stage 2: Users (needed by all other tables)
        $report['users'] = (new UserEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync($entityOptions)['processed'];

        // Stage 3: Core entities
        $report['companies'] = (new CompanyEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync($entityOptions)['processed'];
        $report['contacts'] = (new ContactEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync($entityOptions)['processed'];
        $report['leads'] = (new LeadEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync($entityOptions)['processed'];

        // Stage 4: Customer-related
        $report['customers'] = (new CustomerEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync($entityOptions)['processed'];
        $report['segments'] = (new SegmentEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync($entityOptions)['processed'];

        // Stage 5: Other entities
        $report['calls'] = (new CallEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync($entityOptions)['processed'];
        $report['tasks'] = (new TaskEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync($entityOptions)['processed'];
        $report['transactions'] = (new TransactionEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync($entityOptions)['processed'];
        $report['elements'] = (new ElementEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync($entityOptions)['processed'];
        $report['unsorted'] = (new UnsortedEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync($entityOptions)['processed'];

        // Stage 6: Fact tables
        $this->syncFacts($report, $options);

        return $report;
    }

    /**
     * Incremental synchronization — only entities modified since the given date.
     */
    public function incrementalSync(DateTime $since, array $options = []): array
    {
        $options['since'] = $since;

        return $this->fullSync($options);
    }

    /**
     * Synchronize only fact tables (requires dimension tables to be populated).
     */
    public function syncFacts(array &$report, array $options = []): void
    {
        $report['calls_facts'] = (new CallFactEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync()['processed'];
        $report['contacts_facts'] = (new ContactFactEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync()['processed'];
        $report['companies_facts'] = (new CompanyFactEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync()['processed'];
        $report['leads_facts'] = (new LeadFactEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync()['processed'];
        $report['segments_facts'] = (new SegmentFactEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync()['processed'];
        $report['customers_facts'] = (new CustomerFactEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync()['processed'];
        $report['transactions_facts'] = (new TransactionFactEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync()['processed'];
        $report['tasks_facts'] = (new TaskFactEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync()['processed'];
        $report['transaction_elements_facts'] = (new TransactionElementFactEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync()['processed'];
        $report['lead_elements_facts'] = (new LeadElementFactEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync()['processed'];
        $report['customer_elements_facts'] = (new CustomerElementFactEtlService($this->apiClient, $this->db, $this->accountId))
            ->sync()['processed'];
    }
}
