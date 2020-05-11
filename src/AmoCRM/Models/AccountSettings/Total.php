<?php

namespace AmoCRM\Models\AccountSettings;

use Illuminate\Contracts\Support\Arrayable;

class Total implements Arrayable
{
    /**
     * @var int
     */
    protected $contacts;

    /**
     * @var int
     */
    protected $companies;

    /**
     * @var int
     */
    protected $leads;

    /**
     * @var int
     */
    protected $activeLeads;

    /**
     * @var int
     */
    protected $notes;

    /**
     * @var int
     */
    protected $tasks;

    public function __construct(
        int $contacts,
        int $companies,
        int $leads,
        int $activeLeads,
        int $notes,
        int $tasks
    ) {
        $this->contacts = $contacts;
        $this->companies = $companies;
        $this->leads = $leads;
        $this->activeLeads = $activeLeads;
        $this->notes = $notes;
        $this->tasks = $tasks;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'contacts' => $this->contacts,
            'companies' => $this->companies,
            'leads' => $this->leads,
            'active_leads' => $this->activeLeads,
            'notes' => $this->notes,
            'tasks' => $this->tasks,
        ];
    }

    /**
     * @return int
     */
    public function getContacts(): int
    {
        return $this->contacts;
    }

    /**
     * @return int
     */
    public function getCompanies(): int
    {
        return $this->companies;
    }

    /**
     * @return int
     */
    public function getLeads(): int
    {
        return $this->leads;
    }

    /**
     * @return int
     */
    public function getActiveLeads(): int
    {
        return $this->activeLeads;
    }

    /**
     * @return int
     */
    public function getNotes(): int
    {
        return $this->notes;
    }

    /**
     * @return int
     */
    public function getTasks(): int
    {
        return $this->tasks;
    }
}
