<?php

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasOrderInterface;
use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\ArrayOrNumericFilterTrait;
use AmoCRM\Filters\Traits\ArrayOrStringFilterTrait;
use AmoCRM\Filters\Traits\OrderTrait;
use AmoCRM\Filters\Traits\PagesFilterTrait;

/**
 * Supports pagination and filtering custom_fields by types.
 */
class CustomFieldsFilter extends BaseEntityFilter implements HasPagesInterface, HasOrderInterface
{
    use OrderTrait;
    use PagesFilterTrait;
    use ArrayOrStringFilterTrait;
    use ArrayOrNumericFilterTrait;

    /**
     * @var null|string[] An array of custom_field types.
     */
    private $types = null;

    /**
     * @var null|int[]
     */
    private $ids = null;

    /**
     * @return int[]|null
     */
    public function getIds(): ?array
    {
        return $this->ids;
    }

    public function setIds(array $ids): self
    {
        $this->ids = $this->parseArrayOrNumberFilter($ids);

        return $this;
    }

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

        if (!empty($this->getIds())) {
            $filter['filter']['id'] = $this->getIds();
        }

        if (!empty($this->types)) {
            $filter['filter']['type'] = $this->types;
        }

        if (!is_null($this->getOrder())) {
            $filter['order'] = $this->getOrder();
        }

        return $this->buildPagesFilter($filter);
    }
}
