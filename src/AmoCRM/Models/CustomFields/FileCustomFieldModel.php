<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFields;

/**
 * @since Release Spring 2022
 */
class FileCustomFieldModel extends CustomFieldModel
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_FILE;
    }
}
