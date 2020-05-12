<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueCollections;

/**
 * Передавая данный объект, можно удалить значение поля (обнулить)
 * Class NullCustomFieldValueCollection
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueCollections
 */
class NullCustomFieldValueCollection extends BaseCustomFieldValueCollection
{
    public function toApi(): ?array
    {
        return null;
    }
}
