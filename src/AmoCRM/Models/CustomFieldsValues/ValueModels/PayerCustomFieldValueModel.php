<?php

declare(strict_types=1);

namespace AmoCRM\Models\CustomFieldsValues\ValueModels;

use AmoCRM\Enum\PayerCustomFieldsTypesEnums;

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
    public const TAX_REG_REASON_CODE = 'tax_registration_reason_code';

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
     * @var string БИК
     */
    public const BANK_CODE = 'bank_code';

    /**
     * @var string УНП - ИНН в Беларуси
     */
    public const UNP = 'unp';

    /**
     * @var string БИН - ИНН в Казахстане
     */
    public const BIN = 'bin';

    /**
     * @var string ЕГРПОУ - ИНН в Украине
     */
    public const EGRPOU = 'egrpou';

    /**
     * @var string Фактический адрес
     */
    public const REAL_ADDRESS = 'real_address';

    /**
     * @var string МФО
     */
    public const MFO = 'mfo';

    /**
     * @var string Расчетный счет
     */
    public const BANK_ACCOUNT_NUMBER = 'bank_account_number';

    /**
     * @var string ОКЭД
     */
    public const OKED = 'oked';

    /**
     * @var string Директор
     */
    public const DIRECTOR = 'director';

    /**
     * @var string Номер телефона
     */
    public const PHONE = 'phone';

    /**
     * @var string Адрес электронной почты
     */
    public const EMAIL = 'email';

    /**
     * @var string Тип плательщика (юр. лицо / физ. лицо)
     */
    public const TYPE = 'type';

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
     * @var string|null
     */
    protected $bankCode;

    /**
     * @var string|null
     */
    protected $unp;

    /**
     * @var string|null
     */
    protected $bin;

    /**
     * @var string|null
     */
    protected $egrpou;

    /**
     * @var string|null
     */
    protected $realAddress;

    /**
     * @var string|null
     */
    protected $mfo;

    /**
     * @var string|null
     */
    protected $bankAccountNumber;

    /**
     * @var string|null
     */
    protected $oked;

    /**
     * @var string|null
     */
    protected $phone;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $director;

    /**
     * @var string|null
     *
     * @see PayerCustomFieldsTypesEnums
     */
    protected $type;

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
            ->setEntityId($val[self::ENTITY_ID] ?? null)
            ->setBankCode($val[self::BANK_CODE] ?? null)
            ->setUnp($val[self::UNP] ?? null)
            ->setBin($val[self::BIN] ?? null)
            ->setEgrpou($val[self::EGRPOU] ?? null)
            ->setRealAddress($val[self::REAL_ADDRESS] ?? null)
            ->setMfo($val[self::MFO] ?? null)
            ->setBankAccountNumber($val[self::BANK_ACCOUNT_NUMBER] ?? null)
            ->setOked($val[self::OKED] ?? null)
            ->setPhone($val[self::PHONE] ?? null)
            ->setEmail($val[self::EMAIL] ?? null)
            ->setDirector($val[self::DIRECTOR] ?? null)
            ->setType($val[self::TYPE] ?? null);

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
     * @return string|null
     */
    public function getBankCode(): ?string
    {
        return $this->bankCode;
    }

    /**
     * @param string|null $bankCode
     *
     * @return PayerCustomFieldValueModel
     */
    public function setBankCode(?string $bankCode): PayerCustomFieldValueModel
    {
        $this->bankCode = $bankCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUnp(): ?string
    {
        return $this->unp;
    }

    /**
     * @param string|null $unp
     *
     * @return PayerCustomFieldValueModel
     */
    public function setUnp(?string $unp): PayerCustomFieldValueModel
    {
        $this->unp = $unp;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBin(): ?string
    {
        return $this->bin;
    }

    /**
     * @param string|null $bin
     *
     * @return PayerCustomFieldValueModel
     */
    public function setBin(?string $bin): PayerCustomFieldValueModel
    {
        $this->bin = $bin;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEgrpou(): ?string
    {
        return $this->egrpou;
    }

    /**
     * @param string|null $egrpou
     *
     * @return PayerCustomFieldValueModel
     */
    public function setEgrpou(?string $egrpou): PayerCustomFieldValueModel
    {
        $this->egrpou = $egrpou;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRealAddress(): ?string
    {
        return $this->realAddress;
    }

    /**
     * @param string|null $realAddress
     *
     * @return PayerCustomFieldValueModel
     */
    public function setRealAddress(?string $realAddress): PayerCustomFieldValueModel
    {
        $this->realAddress = $realAddress;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMfo(): ?string
    {
        return $this->mfo;
    }

    /**
     * @param string|null $mfo
     *
     * @return PayerCustomFieldValueModel
     */
    public function setMfo(?string $mfo): PayerCustomFieldValueModel
    {
        $this->mfo = $mfo;

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
     * @return PayerCustomFieldValueModel
     */
    public function setBankAccountNumber(?string $bankAccountNumber): PayerCustomFieldValueModel
    {
        $this->bankAccountNumber = $bankAccountNumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOked(): ?string
    {
        return $this->oked;
    }

    /**
     * @param string|null $oked
     *
     * @return PayerCustomFieldValueModel
     */
    public function setOked(?string $oked): PayerCustomFieldValueModel
    {
        $this->oked = $oked;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     *
     * @return PayerCustomFieldValueModel
     */
    public function setPhone(?string $phone): PayerCustomFieldValueModel
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     *
     * @return PayerCustomFieldValueModel
     */
    public function setEmail(?string $email): PayerCustomFieldValueModel
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDirector(): ?string
    {
        return $this->director;
    }

    /**
     * @param string|null $director
     *
     * @return PayerCustomFieldValueModel
     */
    public function setDirector(?string $director): PayerCustomFieldValueModel
    {
        $this->director = $director;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     *
     * @return PayerCustomFieldValueModel
     */
    public function setType(?string $type): PayerCustomFieldValueModel
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            self::NAME => $this->getName(),
            self::VAT_ID => $this->getVatId(),
            self::KPP => $this->getKpp(),
            self::TAX_REG_REASON_CODE => $this->getTaxRegistrationReasonCode(),
            self::ADDRESS => $this->getAddress(),
            self::ENTITY_TYPE => $this->getEntityType(),
            self::ENTITY_ID => $this->getEntityId(),
            self::BANK_CODE => $this->getBankCode(),
            self::UNP => $this->getUnp(),
            self::BIN => $this->getBin(),
            self::EGRPOU => $this->getEgrpou(),
            self::REAL_ADDRESS => $this->getRealAddress(),
            self::MFO => $this->getMfo(),
            self::BANK_ACCOUNT_NUMBER => $this->getBankAccountNumber(),
            self::OKED => $this->getOked(),
            self::PHONE => $this->getPhone(),
            self::EMAIL => $this->getEmail(),
            self::DIRECTOR => $this->getDirector(),
        ];

        // Временный костыль, пока всем плательщикам не проставим тип
        if (!is_null($this->getType())) {
            $data[self::TYPE] = $this->getType();
        }

        return $data;
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
