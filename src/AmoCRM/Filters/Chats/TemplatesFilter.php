<?php

declare(strict_types=1);

namespace AmoCRM\Filters\Chats;

use AmoCRM\Filters\BaseEntityFilter;

class TemplatesFilter extends BaseEntityFilter
{
    /**
     * @var string[]|null
     */
    private $externalIds;

    /**
     * @return array|null
     */
    public function getExternalIds(): ?array
    {
        return $this->externalIds;
    }

    /**
     * @param string[]|null $externalIds
     *
     * @return TemplatesFilter
     */
    public function setExternalIds(?array $externalIds): self
    {
        $this->externalIds = $externalIds;

        return $this;
    }

    /**
     * @return array
     */
    public function buildFilter(): array
    {
        $filter = [];

        if (!is_null($this->getExternalIds())) {
            $externalIds = $this->getExternalIds();

            if (count($externalIds) === 1) {
                $externalIds = reset($externalIds);
            }
            $filter['filter']['external_id'] = $externalIds;
        }

        return $filter;
    }
}
