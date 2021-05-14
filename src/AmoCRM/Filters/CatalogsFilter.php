<?php

declare(strict_types=1);

namespace AmoCRM\Filters;

use function is_null;

class CatalogsFilter extends BaseEntityFilter
{
    /** @var null|string */
    private $type = null;

    /**
     * @param null|string $type
     *
     * @return $this
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    public function buildFilter(): array
    {
        $filter = [];

        if (!is_null($this->getType())) {
            $filter['type'] = $this->getType();
        }

        return $filter;
    }
}
