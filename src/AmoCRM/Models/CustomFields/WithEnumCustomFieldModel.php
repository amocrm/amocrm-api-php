<?php

namespace AmoCRM\Models\CustomFields;

use AmoCRM\Collections\CustomFields\CustomFieldEnumsCollection;

/**
 * Class WithEnumCustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class WithEnumCustomFieldModel extends CustomFieldModel
{
    /**
     * @var null|CustomFieldEnumsCollection
     */
    protected $enums;

    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_TEXT;
    }

    /**
     * @return CustomFieldEnumsCollection|null
     */
    public function getEnums(): ?CustomFieldEnumsCollection
    {
        return $this->enums;
    }

    /**
     * @param CustomFieldEnumsCollection|null $enums
     *
     * @return WithEnumCustomFieldModel
     */
    public function setEnums(?CustomFieldEnumsCollection $enums): WithEnumCustomFieldModel
    {
        $this->enums = $enums;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['enums'] = !is_null($this->getEnums()) ? $this->getEnums()->toArray() : null;

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

        if (!is_null($this->getEnums())) {
            $result['enums'] = $this->getEnums()->toApi();
        }

        return $result;
    }

    /**
     * @param array $customField
     *
     * @return CustomFieldModel|WithEnumCustomFieldModel
     */
    public static function fromArray(array $customField): CustomFieldModel
    {
        /** @var WithEnumCustomFieldModel $result */
        $result = parent::fromArray($customField);

        if (!empty($customField['enums'])) {
            $result->setEnums(CustomFieldEnumsCollection::fromArray($customField['enums']));
        }

        return $result;
    }
}
