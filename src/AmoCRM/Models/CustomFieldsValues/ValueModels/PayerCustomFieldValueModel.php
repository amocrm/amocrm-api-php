<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

/**
 * Class PayerCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class PayerCustomFieldValueModel extends BaseArrayCustomFieldValueModel
{
    /**
     * @var string ФИО плательщика / Название компании-плательщика
     */
    public const NAME = 'name';

    /**
     * @var string ИНН
     */
    public const VAT_ID = 'vat_id';

    /**
     * @var string КПП
     */
    public const KPP = 'kpp';

    /**
     * @var string ОГРН / ОГРНИП
     */
    public const TAX_REG_REASON_CODE = 'tax_registrarion_reason_code';

    /**
     * @var string Адрес
     */
    public const ADDRESS = 'address';

    /**
     * @var string Тип связанной сущности (contacts или companies)
     */
    public const ENTITY_TYPE = 'entity_type';

    /**
     * @var string ID связанной сущности
     */
    public const ENTITY_ID = 'entity_id';

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $vatId;

    /**
     * @var string|null
     */
    protected $kpp;

    /**
     * @var string|null
     */
    protected $taxRegistrationReasonCode;

    /**
     * @var string|null
     */
    protected $address;

    /**
     * @var string|null
     */
    protected $entityType;

    /**
     * @var int|null
     */
    protected $entityId;

    /**
     * @param array|null $value
     *
     * @return BaseCustomFieldValueModel
     */
    public static function fromArray($value): BaseCustomFieldValueModel
    {
        $model = new static();
        $val = $value['value'] ?? $value;

        $model->setName($val[self::NAME] ?? null)
            ->setVatId($val[self::VAT_ID] ?? null)
            ->setKpp($val[self::KPP] ?? null)
            ->setTaxRegistrationReasonCode($val[self::TAX_REG_REASON_CODE] ?? null)
            ->setAddress($val[self::ADDRESS] ?? null)
            ->setEntityType($val[self::ENTITY_TYPE] ?? null)
            ->setEntityId($val[self::ENTITY_ID] ?? null);

        return $model;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return PayerCustomFieldValueModel
     */
    public function setName(?string $name): PayerCustomFieldValueModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVatId(): ?string
    {
        return $this->vatId;
    }

    /**
     * @param string|null $vatId
     *
     * @return PayerCustomFieldValueModel
     */
    public function setVatId(?string $vatId): PayerCustomFieldValueModel
    {
        $this->vatId = $vatId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getKpp(): ?string
    {
        return $this->kpp;
    }

    /**
     * @param string|null $kpp
     *
     * @return PayerCustomFieldValueModel
     */
    public function setKpp(?string $kpp): PayerCustomFieldValueModel
    {
        $this->kpp = $kpp;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTaxRegistrationReasonCode(): ?string
    {
        return $this->taxRegistrationReasonCode;
    }

    /**
     * @param string|null $taxRegistrationReasonCode
     *
     * @return PayerCustomFieldValueModel
     */
    public function setTaxRegistrationReasonCode(?string $taxRegistrationReasonCode): PayerCustomFieldValueModel
    {
        $this->taxRegistrationReasonCode = $taxRegistrationReasonCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     *
     * @return PayerCustomFieldValueModel
     */
    public function setAddress(?string $address): PayerCustomFieldValueModel
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEntityType(): ?string
    {
        return $this->entityType;
    }

    /**
     * @param string|null $entityType
     *
     * @return PayerCustomFieldValueModel
     */
    public function setEntityType(?string $entityType): PayerCustomFieldValueModel
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    /**
     * @param int|null $entityId
     *
     * @return PayerCustomFieldValueModel
     */
    public function setEntityId(?int $entityId): PayerCustomFieldValueModel
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::NAME => $this->getName(),
            self::VAT_ID => $this->getVatId(),
            self::KPP => $this->getKpp(),
            self::TAX_REG_REASON_CODE => $this->getTaxRegistrationReasonCode(),
            self::ADDRESS => $this->getAddress(),
            self::ENTITY_TYPE => $this->getEntityType(),
            self::ENTITY_ID => $this->getEntityId(),
        ];
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->toArray();
    }

    /**
     * @param string|null $requestId
     *
     * @return array
     */
    public function toApi(string $requestId = null): array
    {
        return [
            'value' => $this->getValue(),
        ];
    }
}
