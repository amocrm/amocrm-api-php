<?php

namespace AmoCRM\Models\CustomFields;

use AmoCRM\Collections\CustomFields\CustomFieldNestedCollection;

/**
 * Class CategoryCustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class CategoryCustomFieldModel extends CustomFieldModel
{
    /**
     * @var null|CustomFieldNestedCollection
     */
    protected $nested;

    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_CATEGORY;
    }

    /**
     * @return CustomFieldNestedCollection|null
     */
    public function getNested(): ?CustomFieldNestedCollection
    {
        return $this->nested;
    }

    /**
     * @param CustomFieldNestedCollection|null $nested
     *
     * @return CategoryCustomFieldModel
     */
    public function setNested(?CustomFieldNestedCollection $nested): CategoryCustomFieldModel
    {
        $this->nested = $nested;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['nested'] = !is_null($this->getNested()) ? $this->getNested()->toArray() : null;

        return $result;
    }

    /**
     * @param string|null $requestId
     *
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = parent::toApi($requestId);

        if (!is_null($this->getNested())) {
            $result['nested'] = $this->getNested()->toApi();
        }

        return $result;
    }

    /**
     * @param array $customField
     *
     * @return CustomFieldModel|CategoryCustomFieldModel
     */
    public static function fromArray(array $customField): CustomFieldModel
    {
        /** @var CategoryCustomFieldModel $result */
        $result = parent::fromArray($customField);

        if (!empty($customField['nested'])) {
            $result->setNested(CustomFieldNestedCollection::fromArray($customField['nested']));
        }

        return $result;
    }
}
