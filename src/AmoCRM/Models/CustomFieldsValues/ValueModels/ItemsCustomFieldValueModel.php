<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

/**
 * Class ItemsCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class ItemsCustomFieldValueModel extends BaseCustomFieldValueModel
{
    public const FIELD_SKU            = 'sku';
    public const FIELD_DESCRIPTION    = 'description';
    public const FIELD_UNIT_PRICE     = 'unit_price';
    public const FIELD_QUANTITY       = 'quantity';
    public const FIELD_UNIT_TYPE      = 'unit_type';
    public const FIELD_DISCOUNT       = 'discount';
    public const FIELD_VAT_RATE_ID    = 'vat_rate_id';
    public const FIELD_EXTERNAL_UID   = 'external_uid';

    public const FIELD_DISCOUNT_TYPE_PERCENTAGE  = 'percentage';
    public const FIELD_DISCOUNT_TYPE_AMOUNT      = 'amount';

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
     * @var int|null
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
     * @param int|string|null $value
     *
     * @return BaseCustomFieldValueModel
     */
    public static function fromArray($value): BaseCustomFieldValueModel
    {
        $model = new self();

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
            ->setExternalUid($value['value'][self::FIELD_EXTERNAL_UID] ?? null)

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
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $quantity
     *
     * @return ItemsCustomFieldValueModel
     */
    public function setQuantity(?int $quantity): ItemsCustomFieldValueModel
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
            self::FIELD_EXTERNAL_UID => $this->getExternalUid(),
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
