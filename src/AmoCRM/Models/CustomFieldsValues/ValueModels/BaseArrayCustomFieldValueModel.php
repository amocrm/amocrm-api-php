<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

/**
 * Class BaseArrayCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class BaseArrayCustomFieldValueModel extends BaseCustomFieldValueModel
{
    /**
     * @var array
     */
    protected $value;

    /**
     * @param array $value
     *
     * @return BaseCustomFieldValueModel
     */
    public static function fromArray($value): BaseCustomFieldValueModel
    {
        $model = new self();

        $value = isset($value['value']) ? (array)$value['value'] : null;

        $model
            ->setValue($value);

        return $model;
    }
}
