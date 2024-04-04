<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

use function in_array;

/**
 * Class ItemsCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class ItemsCustomFieldValueModel extends BaseCustomFieldValueModel
{
    /** Артикул товара */
    public const FIELD_SKU = 'sku';
    /** Описание товара */
    public const FIELD_DESCRIPTION = 'description';
    /** Цена за единицу товара */
    public const FIELD_UNIT_PRICE = 'unit_price';
    /** Количство единиц товара */
    public const FIELD_QUANTITY = 'quantity';
    /** Единица измерения товвара */
    public const FIELD_UNIT_TYPE = 'unit_type';
    /** Скидка на товар */
    public const FIELD_DISCOUNT = 'discount';
    /** Поля подлежит к удалению, хранился ID системы налогооблажения, но больше поле не поддерживается */
    /** @deprecated */
    public const FIELD_VAT_RATE_ID = 'vat_rate_id';
    /** процент НДС */
    public const FIELD_VAT_RATE_VALUE = 'vat_rate_value';
    /** ID товара во внешней системе */
    public const FIELD_EXTERNAL_UID = 'external_uid';
    /** ID элемента списка товаров amoCRM */
    public const FIELD_PRODUCT_ID = 'product_id';
    /** Сколько бонусных баллов будет начислено покупателю, если счет привязан к нему и переходит в статус оплачено */
    public const FIELD_BONUS_POINTS_PER_PURCHASE = 'bonus_points_per_purchase';
    /** Некая доп. информация, относящаяся к товару в рамках заказа */
    public const FIELD_METADATA = 'metadata';

    /** Скдика - процент от стоимости товара */
    public const FIELD_DISCOUNT_TYPE_PERCENTAGE = 'percentage';
    /** Скдика - цифра от стоимости товара */
    public const FIELD_DISCOUNT_TYPE_AMOUNT = 'amount';
    /** Произошел ли перерасчет скидки товарной позиции */
    public const FIELD_IS_DISCOUNT_RECALCULATED = 'is_discount_recalculated';
    /** Произошел ли перерасчет суммы товарной позиции при скидке 0 */
    public const FIELD_IS_TOTAL_SUM_RECALCULATED = 'is_total_sum_recalculated';
    /** Сумма товарной позиции */
    public const FIELD_TOTAL_SUM = 'total_sum';

    /**
     * @var string|int|null
     */
    protected $sku;

    /**
     * @var string|int|null
     */
    protected $description;

    /**
     * @var float|null
     */
    protected $unitPrice;

    /**
     * @var float|null
     */
    protected $quantity;

    /**
     * @var string|null
     */
    protected $unitType;

    /**
     * @var array|null
     */
    protected $discount;

    /**
     * @var string|int|null
     */
    protected $vatRateId;

    /**
     * @var string|int|null
     */
    protected $externalUid;

    /**
     * @var int|null
     */
    protected $productId;

    /**
     * @var int|null
     */
    protected $vatRateValue;

    /** @var int|null */
    protected $bonusPointsPerPurchase;

    /** @var array|null */
    protected $metadata;

    /** @var bool */
    protected $isDiscountRecalculated;

    /** @var bool */
    protected $isTotalSumRecalculated;

    /** @var float */
    protected $totalSum;

    /**
     * @param int|string|null $value
     *
     * @return ItemsCustomFieldValueModel
     */
    public static function fromArray($value): BaseCustomFieldValueModel
    {
        $model = new static();
        $val = $value['value'] ?? $value;

        $discount = [
            'type' => isset($val[self::FIELD_DISCOUNT]['type'])
            && in_array(
                $val[self::FIELD_DISCOUNT]['type'],
                [
                    self::FIELD_DISCOUNT_TYPE_AMOUNT,
                    self::FIELD_DISCOUNT_TYPE_PERCENTAGE,
                ]
            ) ? $val[self::FIELD_DISCOUNT]['type'] : null,
            'value' => $val[self::FIELD_DISCOUNT]['value'] ?? null,
        ];

        $model
            ->setSku($val[self::FIELD_SKU] ?? null)
            ->setDescription($val[self::FIELD_DESCRIPTION] ?? null)
            ->setUnitPrice($val[self::FIELD_UNIT_PRICE] ?? null)
            ->setQuantity($val[self::FIELD_QUANTITY] ?? null)
            ->setUnitType($val[self::FIELD_UNIT_TYPE] ?? null)
            ->setDiscount($discount)
            ->setVatRateId($val[self::FIELD_VAT_RATE_ID] ?? null)
            ->setVatRateValue($val[self::FIELD_VAT_RATE_VALUE] ?? null)
            ->setExternalUid($val[self::FIELD_EXTERNAL_UID] ?? null)
            ->setProductId($val[self::FIELD_PRODUCT_ID] ?? null)
            ->setBonusPointsPerPurchase($val[self::FIELD_BONUS_POINTS_PER_PURCHASE] ?? null)
            ->setMetadata($val[self::FIELD_METADATA] ?? null)
            ->setIsDiscountRecalculated($val[self::FIELD_IS_DISCOUNT_RECALCULATED] ?? false)
            ->setIsTotalSumRecalculated($val[self::FIELD_IS_TOTAL_SUM_RECALCULATED] ?? false);

        if (isset($val[self::FIELD_TOTAL_SUM])) {
            $model->setTotalSum((float)$val[self::FIELD_TOTAL_SUM]);
        }

        return $model;
    }

    /**
     * @return int|string|null
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param int|string|null $sku
     *
     * @return ItemsCustomFieldValueModel
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int|string|null $description
     *
     * @return ItemsCustomFieldValueModel
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    /**
     * @param float|null $unitPrice
     *
     * @return ItemsCustomFieldValueModel
     */
    public function setUnitPrice(?float $unitPrice): ItemsCustomFieldValueModel
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    /**
     * @param float|null $quantity
     *
     * @return ItemsCustomFieldValueModel
     */
    public function setQuantity(?float $quantity): ItemsCustomFieldValueModel
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUnitType(): ?string
    {
        return $this->unitType;
    }

    /**
     * @param string|null $unitType
     *
     * @return ItemsCustomFieldValueModel
     */
    public function setUnitType(?string $unitType): ItemsCustomFieldValueModel
    {
        $this->unitType = $unitType;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getDiscount(): ?array
    {
        return $this->discount;
    }

    /**
     * @param array|null $discount
     *
     * @return ItemsCustomFieldValueModel
     */
    public function setDiscount(?array $discount): ItemsCustomFieldValueModel
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getVatRateId()
    {
        return $this->vatRateId;
    }

    /**
     * @param int|string|null $vatRateId
     *
     * @return ItemsCustomFieldValueModel
     */
    public function setVatRateId($vatRateId)
    {
        $this->vatRateId = $vatRateId;

        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getExternalUid()
    {
        return $this->externalUid;
    }

    /**
     * @param int|string|null $externalUid
     *
     * @return ItemsCustomFieldValueModel
     */
    public function setExternalUid($externalUid)
    {
        $this->externalUid = $externalUid;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        return $this->productId;
    }

    /**
     * @param int|null $productId
     *
     * @return ItemsCustomFieldValueModel
     */
    public function setProductId(?int $productId): ItemsCustomFieldValueModel
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getVatRateValue(): ?int
    {
        return $this->vatRateValue;
    }

    /**
     * @param int|null $vatRateValue
     *
     * @return ItemsCustomFieldValueModel
     */
    public function setVatRateValue(?int $vatRateValue): ItemsCustomFieldValueModel
    {
        $this->vatRateValue = $vatRateValue;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getBonusPointsPerPurchase(): ?int
    {
        return $this->bonusPointsPerPurchase;
    }

    /**
     * @param int|null $bonusPointsPerPurchase
     *
     * @return ItemsCustomFieldValueModel
     */
    public function setBonusPointsPerPurchase(?int $bonusPointsPerPurchase): ItemsCustomFieldValueModel
    {
        $this->bonusPointsPerPurchase = $bonusPointsPerPurchase;

        return $this;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): ItemsCustomFieldValueModel
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDiscountRecalculated(): bool
    {
        return $this->isDiscountRecalculated ?? false;
    }

    /**
     * @param bool $isDiscountRecalculated
     *
     * @return ItemsCustomFieldValueModel
     */
    private function setIsDiscountRecalculated(bool $isDiscountRecalculated): ItemsCustomFieldValueModel
    {
        $this->isDiscountRecalculated = $isDiscountRecalculated;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsTotalSumRecalculated(): bool
    {
        return $this->isTotalSumRecalculated ?? false;
    }

    /**
     * @param bool $isTotalSumRecalculated
     *
     * @return ItemsCustomFieldValueModel
     */
    private function setIsTotalSumRecalculated(bool $isTotalSumRecalculated): ItemsCustomFieldValueModel
    {
        $this->isTotalSumRecalculated = $isTotalSumRecalculated;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalSum(): float
    {
        return $this->totalSum ?? 0;
    }

    /**
     * @param float $totalSum
     *
     * @return ItemsCustomFieldValueModel
     */
    private function setTotalSum(float $totalSum): ItemsCustomFieldValueModel
    {
        $this->totalSum = $totalSum;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_SKU => $this->getSku(),
            self::FIELD_DESCRIPTION => $this->getDescription(),
            self::FIELD_UNIT_PRICE => $this->getUnitPrice(),
            self::FIELD_QUANTITY => $this->getQuantity(),
            self::FIELD_UNIT_TYPE => $this->getUnitType(),
            self::FIELD_DISCOUNT => $this->getDiscount(),
            self::FIELD_VAT_RATE_ID => $this->getVatRateId(),
            self::FIELD_VAT_RATE_VALUE => $this->getVatRateValue(),
            self::FIELD_EXTERNAL_UID => $this->getExternalUid(),
            self::FIELD_PRODUCT_ID => $this->getProductId(),
            self::FIELD_BONUS_POINTS_PER_PURCHASE => $this->getBonusPointsPerPurchase(),
            self::FIELD_METADATA => $this->getMetadata(),
            self::FIELD_IS_DISCOUNT_RECALCULATED => $this->getIsDiscountRecalculated(),
            self::FIELD_IS_TOTAL_SUM_RECALCULATED => $this->getIsTotalSumRecalculated(),
            self::FIELD_TOTAL_SUM => $this->getTotalSum(),
        ];
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->toArray();
    }


    public function toApi(string $requestId = null): array
    {
        $result = [
            self::FIELD_SKU => $this->getSku(),
            self::FIELD_DESCRIPTION => $this->getDescription(),
            self::FIELD_UNIT_PRICE => $this->getUnitPrice(),
            self::FIELD_QUANTITY => $this->getQuantity(),
            self::FIELD_UNIT_TYPE => $this->getUnitType(),
            self::FIELD_DISCOUNT => $this->getDiscount(),
            self::FIELD_VAT_RATE_ID => $this->getVatRateId(),
            self::FIELD_VAT_RATE_VALUE => $this->getVatRateValue(),
            self::FIELD_EXTERNAL_UID => $this->getExternalUid(),
            self::FIELD_PRODUCT_ID => $this->getProductId(),
            self::FIELD_BONUS_POINTS_PER_PURCHASE => $this->getBonusPointsPerPurchase(),
            self::FIELD_METADATA => $this->getMetadata(),
        ];

        return [
            'value' => $result,
        ];
    }
}
