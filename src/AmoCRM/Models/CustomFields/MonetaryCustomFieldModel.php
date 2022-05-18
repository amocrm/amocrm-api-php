<?php

namespace AmoCRM\Models\CustomFields;

/**
 * @since Release Spring 2022
 */
class MonetaryCustomFieldModel extends CustomFieldModel
{
    /**
     * @var string
     */
    protected $currency;

    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomFieldModel::TYPE_MONETARY;
    }

    /**
     * Установка значения валюты в поле
     *
     * @param string $currency
     *
     * @return $this
     */
    public function setCurrency(string $currency): MonetaryCustomFieldModel
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string Возвращает настроенную валюту
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['currency'] = $this->getCurrency();

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

        $result['currency'] = $this->getCurrency();

        return $result;
    }

    /**
     * @param array $customField
     *
     * @return MonetaryCustomFieldModel
     */
    public static function fromArray(array $customField): CustomFieldModel
    {
        /** @var MonetaryCustomFieldModel $result */
        $result = parent::fromArray($customField);

        $result->setCurrency($customField['currency']);

        return $result;
    }
}
