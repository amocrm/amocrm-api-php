<?php

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

/**
 * Class LegalEntityCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class LegalEntityCustomFieldValueModel extends BaseArrayCustomFieldValueModel
{
    /** Имя юр лицы */
    public const NAME = 'name';
    /** Тип юр лица */
    public const LEGAL_ENTITY_TYPE = 'entity_type';
    /** ИНН */
    public const VAT_ID = 'vat_id';
    /** ОГРН/ОГРНИП */
    public const TAX_REG_REASON_CODE = 'tax_registration_reason_code';
    /** Адрес юр лица */
    public const ADDRESS = 'address';
    /** КПП */
    public const KPP = 'kpp';
    /** БИК */
    public const BANK_CODE = 'bank_code';
    /** Идентификатор юр лица во внешней учетной системе */
    public const EXTERNAL_UID = 'external_uid';

    /** Частное лицо */
    public const LEGAL_ENTITY_TYPE_SOLE_PROPRIETORSHIP = 1;
    /** Юр. лицо */
    public const LEGAL_ENTITY_TYPE_JURIDICAL_PERSON = 2;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var int|null
     */
    protected $legalEntityType;

    /**
     * @var string|null
     */
    protected $vatId;

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
    protected $kpp;

    /**
     * @var string|null
     */
    protected $bankCode;

    /**
     * @var string|null
     */
    protected $externalUid;

    /**
     * @param array|null $value
     *
     * @return BaseCustomFieldValueModel
     */
    public static function fromArray($value): BaseCustomFieldValueModel
    {
        $model = new static();

        $model
            ->setName($value['value'][self::NAME] ?? null)
            ->setLegalEntityType($value['value'][self::LEGAL_ENTITY_TYPE] ?? null)
            ->setVatId($value['value'][self::VAT_ID] ?? null)
            ->setTaxRegistrationReasonCode($value['value'][self::TAX_REG_REASON_CODE] ?? null)
            ->setAddress($value['value'][self::ADDRESS] ?? null)
            ->setKpp($value['value'][self::KPP] ?? null)
            ->setBankCode($value['value'][self::BANK_CODE] ?? null)
            ->setExternalUid($value['value'][self::EXTERNAL_UID] ?? null)
        ;

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
     * @return LegalEntityCustomFieldValueModel
     */
    public function setName(?string $name): LegalEntityCustomFieldValueModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLegalEntityType(): ?int
    {
        return $this->legalEntityType;
    }

    /**
     * @param int|null $legalEntityType
     *
     * @return LegalEntityCustomFieldValueModel
     */
    public function setLegalEntityType(?int $legalEntityType): LegalEntityCustomFieldValueModel
    {
        $this->legalEntityType = $legalEntityType;

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
     * @return LegalEntityCustomFieldValueModel
     */
    public function setVatId(?string $vatId): LegalEntityCustomFieldValueModel
    {
        $this->vatId = $vatId;

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
     * @return LegalEntityCustomFieldValueModel
     */
    public function setTaxRegistrationReasonCode(?string $taxRegistrationReasonCode): LegalEntityCustomFieldValueModel
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
     * @return LegalEntityCustomFieldValueModel
     */
    public function setAddress(?string $address): LegalEntityCustomFieldValueModel
    {
        $this->address = $address;

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
     * @return LegalEntityCustomFieldValueModel
     */
    public function setKpp(?string $kpp): LegalEntityCustomFieldValueModel
    {
        $this->kpp = $kpp;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBankCode(): ?string
    {
        return $this->bankCode;
    }

    /**
     * @param string|null $bankCode
     *
     * @return LegalEntityCustomFieldValueModel
     */
    public function setBankCode(?string $bankCode): LegalEntityCustomFieldValueModel
    {
        $this->bankCode = $bankCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExternalUid(): ?string
    {
        return $this->externalUid;
    }

    /**
     * @param string|null $externalUid
     *
     * @return LegalEntityCustomFieldValueModel
     */
    public function setExternalUid(?string $externalUid): LegalEntityCustomFieldValueModel
    {
        $this->externalUid = $externalUid;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::NAME => $this->getName(),
            self::LEGAL_ENTITY_TYPE => $this->getLegalEntityType(),
            self::VAT_ID => $this->getVatId(),
            self::TAX_REG_REASON_CODE => $this->getTaxRegistrationReasonCode(),
            self::ADDRESS => $this->getAddress(),
            self::KPP => $this->getKpp(),
            self::BANK_CODE => $this->getBankCode(),
            self::EXTERNAL_UID => $this->getExternalUid(),
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
