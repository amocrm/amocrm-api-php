<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFields;

/**
 * Модель поля вложенных списков
 *
 * @since Release Spring 2022
 */
class ChainedListCustomFieldModel extends CustomFieldModel
{
    /** @var ChainedLists<ChainedList> */
    private $chainedLists;

    /**
     * @return string Тип поля
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_CHAINED_LIST;
    }

    /**
     * Установка вложенных списков
     *
     * @param ChainedLists<ChainedList> $chainedLists
     *
     * @return void
     */
    public function setChainedLists(ChainedLists $chainedLists): void
    {
        $this->chainedLists = $chainedLists;
    }

    /**
     * @return ChainedLists<ChainedList> Возвращает настроенные вложенные списки
     */
    public function getChainedLists(): ChainedLists
    {
        return $this->chainedLists;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['chained_lists'] = $this->getChainedLists()->toArray();

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

        $result['chained_lists'] = $this->getChainedLists()->toArray();

        return $result;
    }

    /**
     * @param array $customField
     *
     * @return ChainedListCustomFieldModel
     */
    public static function fromArray(array $customField): CustomFieldModel
    {
        /** @var ChainedListCustomFieldModel $result */
        $result = parent::fromArray($customField);

        $result->setChainedLists(
            ChainedLists::fromArray(
                (array) ($customField['chained_lists'] ?? [])
            )
        );

        return $result;
    }
}
