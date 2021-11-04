<?php

namespace AmoCRM\Filters;

class SourcesFilter extends BaseEntityFilter
{
    /**
     * @var string[]|null
     */
    private $externalIds;

    /**
     * @return string|null
     */
    public function getExternalIds(): ?array
    {
        return $this->externalIds;
    }

    /**
     * @param string[]|null $externalIds
     *
     * @return SourcesFilter
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
