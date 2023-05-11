<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

use AmoCRM\Models\CatalogElementModel;

/**
 * Class SupplierCustomFieldValueModel
 *
 * @package AmoCRM\Models\CustomFieldsValues\ValueModels
 */
class SupplierCustomFieldValueModel extends BaseArrayCustomFieldValueModel
{
    /**
     * @var string ID связанного элемента каталога юр. лиц
     */
    public const ENTITY_ID = 'entity_id';

    /**
     * @var string Название поставщика. Заполняется только при передаче флага with = filled_suppliers_fields
     *
     * @see CatalogElementModel::SUPPLIER_FIELD_VALUES
     */
    public const NAME = 'name';

    /**
     * @var string ИНН. Заполняется только при передаче флага with = filled_suppliers_fields
     *
     * @see CatalogElementModel::SUPPLIER_FIELD_VALUES
     */
    public const VAT_ID = 'vat_id';

    /**
     * @var string КПП. Заполняется только при передаче флага with = filled_suppliers_fields
     *
     * @see CatalogElementModel::SUPPLIER_FIELD_VALUES
     */
    public const KPP = 'kpp';

    /**
     * @var string ОГРН / ОГРНИП. Заполняется только при передаче флага with = filled_suppliers_fields
     *
     * @see CatalogElementModel::SUPPLIER_FIELD_VALUES
     */
    public const TAX_REG_REASON_CODE = 'tax_registration_reason_code';

    /**
     * @var string БИК. Заполняется только при передаче флага with = filled_suppliers_fields
     *
     * @see CatalogElementModel::SUPPLIER_FIELD_VALUES
     */
    public const BANK_CODE = 'bank_code';

    /**
     * @var string Адрес. Заполняется только при передаче флага with = filled_suppliers_fields
     *
     * @see CatalogElementModel::SUPPLIER_FIELD_VALUES
     */
    public const ADDRESS = 'address';

    /**
     * @var string Расчетный счет. Заполняется только при передаче флага with = filled_suppliers_fields
     *
     * @see CatalogElementModel::SUPPLIER_FIELD_VALUES
     */
    public const BANK_ACCOUNT_NUMBER = 'bank_account_number';

    /**
     * @var int
     */
    protected $entityId;

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
    protected $bankCode;

    /**
     * @var string|null
     */
    protected $address;

    /**
     * @var string|null
     */
    protected $bankAccountNumber;

    /**
     * @param array|null $value
     *
     * @return BaseCustomFieldValueModel
     */
    public static function fromArray($value): BaseCustomFieldValueModel
    {
        $model = new static();
        $val = $value['value'] ?? $value;

        $model->setEntityId($val[self::ENTITY_ID])
            ->setName($val[self::NAME] ?? null)
            ->setVatId($val[self::VAT_ID] ?? null)
            ->setKpp($val[self::KPP] ?? null)
            ->setTaxRegistrationReasonCode($val[self::TAX_REG_REASON_CODE] ?? null)
            ->setBankCode($val[self::BANK_CODE] ?? null)
            ->setAddress($val[self::ADDRESS] ?? null)
            ->setBankAccountNumber($val[self::BANK_ACCOUNT_NUMBER] ?? null);

        return $model;
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->entityId;
    }

    /**
     * @param int $entityId
     *
     * @return SupplierCustomFieldValueModel
     */
    public function setEntityId(int $entityId): SupplierCustomFieldValueModel
    {
        $this->entityId = $entityId;

        return $this;
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
     * @return SupplierCustomFieldValueModel
     */
    protected function setName(?string $name): SupplierCustomFieldValueModel
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
     * @return SupplierCustomFieldValueModel
     */
    protected function setVatId(?string $vatId): SupplierCustomFieldValueModel
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
     * @return SupplierCustomFieldValueModel
     */
    protected function setKpp(?string $kpp): SupplierCustomFieldValueModel
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
     * @return SupplierCustomFieldValueModel
     */
    protected function setTaxRegistrationReasonCode(?string $taxRegistrationReasonCode): SupplierCustomFieldValueModel
    {
        $this->taxRegistrationReasonCode = $taxRegistrationReasonCode;

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
     * @return SupplierCustomFieldValueModel
     */
    protected function setBankCode(?string $bankCode): SupplierCustomFieldValueModel
    {
        $this->bankCode = $bankCode;

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
     * @return SupplierCustomFieldValueModel
     */
    protected function setAddress(?string $address): SupplierCustomFieldValueModel
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBankAccountNumber(): ?string
    {
        return $this->bankAccountNumber;
    }

    /**
     * @param string|null $bankAccountNumber
     *
     * @return SupplierCustomFieldValueModel
     */
    protected function setBankAccountNumber(?string $bankAccountNumber): SupplierCustomFieldValueModel
    {
        $this->bankAccountNumber = $bankAccountNumber;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::ENTITY_ID => $this->getEntityId(),
            self::NAME => $this->getName(),
            self::VAT_ID => $this->getVatId(),
            self::KPP => $this->getKpp(),
            self::TAX_REG_REASON_CODE => $this->getTaxRegistrationReasonCode(),
            self::BANK_CODE => $this->getBankCode(),
            self::ADDRESS => $this->getAddress(),
            self::BANK_ACCOUNT_NUMBER => $this->getBankAccountNumber(),
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
            'value' => [
                self::ENTITY_ID => $this->getEntityId(),
            ],
        ];
    }
}
