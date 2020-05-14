<?php

namespace AmoCRM\Models\CustomFields;

/**
 * Class BirthdayCustomFieldModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class BirthdayCustomFieldModel extends CustomFieldModel
{
    public const REMIND_NEVER = 'never';
    public const REMIND_DAY = 'day';
    public const REMIND_WEEK = 'week';
    public const REMIND_MONTH = 'month';

    /**
     * @var string|null
     */
    protected $remind;

    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_BIRTHDAY;
    }

    /**
     * @return null|string
     */
    public function getRemind(): ?string
    {
        return $this->remind;
    }

    /**
     * @param string $remind
     *
     * @return BirthdayCustomFieldModel
     */
    public function setRemind(string $remind): BirthdayCustomFieldModel
    {
        if (
            !in_array(
                $remind,
                [
                    self::REMIND_NEVER,
                    self::REMIND_DAY,
                    self::REMIND_MONTH,
                    self::REMIND_WEEK,
                ]
            )
        ) {
            $remind = self::REMIND_NEVER;
        }

        $this->remind = $remind;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['remind'] = $this->getRemind();

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

        $result['remind'] = $this->getRemind();

        return $result;
    }

    /**
     * @param array $customField
     *
     * @return CustomFieldModel|BirthdayCustomFieldModel
     */
    public static function fromArray(array $customField): CustomFieldModel
    {
        /** @var BirthdayCustomFieldModel $result */
        $result = parent::fromArray($customField);

        if (!empty($customField['remind'])) {
            $result->setRemind($customField['remind']);
        }

        return $result;
    }
}
