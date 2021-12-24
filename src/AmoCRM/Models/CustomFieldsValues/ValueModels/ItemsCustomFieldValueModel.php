<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

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

    /** Скдика - процент от стоимости товара */
    public const FIELD_DISCOUNT_TYPE_PERCENTAGE = 'percentage';
    /** Скдика - цифра от стоимости товара */
    public const FIELD_DISCOUNT_TYPE_AMOUNT = 'amount';

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

    /**
     * @param int|string|null $value
     *
     * @return ItemsCustomFieldValueModel
     */
    public static function fromArray($value): BaseCustomFieldValueModel
    {
        $model = new static();

        $discount = [
            'type' => isset($value['value'][self::FIELD_DISCOUNT]['type'])
                && in_array(
                    $value['value'][self::FIELD_DISCOUNT]['type'],
                    [
                        self::FIELD_DISCOUNT_TYPE_AMOUNT,
                        self::FIELD_DISCOUNT_TYPE_PERCENTAGE,
                    ]
                ) ? $value['value'][self::FIELD_DISCOUNT]['type'] : null,
            'value' => $value['value'][self::FIELD_DISCOUNT]['value'] ?? null,
        ];

        $model
            ->setSku($value['value'][self::FIELD_SKU] ?? null)
            ->setDescription($value['value'][self::FIELD_DESCRIPTION] ?? null)
            ->setUnitPrice($value['value'][self::FIELD_UNIT_PRICE] ?? null)
            ->setQuantity($value['value'][self::FIELD_QUANTITY] ?? null)
            ->setUnitType($value['value'][self::FIELD_UNIT_TYPE] ?? null)
            ->setDiscount($discount)
            ->setVatRateId($value['value'][self::FIELD_VAT_RATE_ID] ?? null)
            ->setVatRateValue($value['value'][self::FIELD_VAT_RATE_VALUE] ?? null)
            ->setExternalUid($value['value'][self::FIELD_EXTERNAL_UID] ?? null)
            ->setProductId($value['value'][self::FIELD_PRODUCT_ID] ?? null)
            ->setBonusPointsPerPurchase($value['value'][self::FIELD_BONUS_POINTS_PER_PURCHASE] ?? null)
        ;

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
        return [
            'value' => $this->getValue(),
        ];
    }
}
