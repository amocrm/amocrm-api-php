<?php

declare(strict_types=1);

namespace AmoCRM\Enum;

class SuppliersCustomFieldsEnums
{
    /** @var string ИНН поставщика */
    public const VAT_ID = 'SUPPLIER_VAT_ID';

    /** @var string КПП поставщика */
    public const TAX_REGISTRATION_REASON_CODE = 'SUPPLIER_TAX_REGISTRATION_REASON_CODE';

    /** @var string ОГРН поставщика */
    public const PRIMARY_STATE_REGISTRATION_NUMBER = 'SUPPLIER_PRIMARY_STATE_REGISTRATION_NUMBER';

    /** @var string БИК поставщика */
    public const BANK_IDENTIFICATION_CODE = 'SUPPLIER_BANK_IDENTIFICATION_CODE';

    /** @var string Расчетный счет */
    public const ACCOUNT_NUMBER = 'SUPPLIER_ACCOUNT_NUMBER';

    /** @var string Юр. адрес */
    public const LEGAL_ADDRESS = 'SUPPLIER_LEGAL_ADDRESS';

    /** @var string Факт. адрес */
    public const REAL_ADDRESS = 'SUPPLIER_REAL_ADDRESS';

    /** @var string ФИО руководителя */
    public const DIRECTOR_NAME = 'SUPPLIER_DIRECTOR_NAME';

    /** @var string Должность руководителя */
    public const DIRECTOR_POST = 'SUPPLIER_DIRECTOR_POST';

    /** @var string ФИО бухгалтера */
    public const ACCOUNTANT_NAME = 'SUPPLIER_ACCOUNTANT_NAME';

    /** @var string Должность бухгалтера */
    public const ACCOUNTANT_POST = 'SUPPLIER_ACCOUNTANT_POST';

    /** @var string Телефон */
    public const PHONE = 'SUPPLIER_PHONE';
}
