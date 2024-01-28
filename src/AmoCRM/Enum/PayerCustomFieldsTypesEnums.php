<?php

declare(strict_types=1);

namespace AmoCRM\Enum;

/**
 * Class PayerCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class PayerCustomFieldsTypesEnums
{
    /** @var string Юридическое лицо */
    public const TYPE_LEGAL = 'legal';

    /** @var string Физическое лицо */
    public const TYPE_INDIVIDUAL = 'individual';

    public static function isValid(string $type): bool
    {
        return in_array(
            $type,
            [
                self::TYPE_LEGAL,
                self::TYPE_INDIVIDUAL
            ],
            true
        );
    }
}
