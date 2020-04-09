<?php

namespace AmoCRM\Models\AccountSettings;

use Illuminate\Contracts\Support\Arrayable;

class AmojoRights implements Arrayable
{
    /**
     * @var bool
     */
    protected $canDirect;

    /**
     * @var bool
     */
    protected $canCreateGroups;

    public function __construct(
        bool $canDirect,
        bool $canCreateGroups
    ) {
        $this->canDirect = $canDirect;
        $this->canCreateGroups = $canCreateGroups;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [
            'can_direct' => $this->isCanDirect(),
            'can_create_groups' => $this->isCanCreateGroups(),
        ];

        return $result;
    }

    /**
     * @return bool
     */
    public function getIsCanDirect(): bool
    {
        return $this->canDirect;
    }

    /**
     * @return bool
     */
    public function getIsCanCreateGroups(): bool
    {
        return $this->canCreateGroups;
    }
}
