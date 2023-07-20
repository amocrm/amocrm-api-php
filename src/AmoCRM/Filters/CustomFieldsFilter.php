<?php

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\ArrayOrStringFilterTrait;
use AmoCRM\Filters\Traits\PagesFilterTrait;

/**
 * Supports pagination and filtering custom_fields by types.
 */
class CustomFieldsFilter extends BaseEntityFilter implements HasPagesInterface
{
    use PagesFilterTrait;
    use ArrayOrStringFilterTrait;

    /**
     * @var null|string[] An array of custom_field types.
     */
    private ?array $types = null;

    /**
     * @return string[]|null
     */
    public function getTypes(): ?array
    {
        return $this->types;
    }

    /**
     * Filters custom_fields by types.
     *
     * @param string[]|null $types Type are defined in `CustomFieldModel`.
     */
    public function setTypes(?array $types): self
    {
        $this->types = $this->parseArrayOrStringFilter($types);

        return $this;
    }

    /**
     * @return array
     */
    public function buildFilter(): array
    {
        $filter = [];

        if (!empty($this->types)) {
            $filter['filter']['type'] = $this->types;
        }

        $filter = $this->buildPagesFilter($filter);

        return $filter;
    }
}
