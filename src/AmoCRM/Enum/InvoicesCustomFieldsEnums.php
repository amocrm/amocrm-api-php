<?php

declare(strict_types=1);

namespace AmoCRM\Enum;

use AmoCRM\Enum\Invoices\BillStatusEnumCode;

class InvoicesCustomFieldsEnums
{
    /**
     * Ниже представлены контсанты с кодами полей списка счетов, которые есть во всех новых списках.
     * Важно отметить, что поля могут быть удалены из интерфейса!
     */
    /** @var string Статус счета */
    public const STATUS = 'BILL_STATUS';
    /** @var string Юр. лицо */
    public const LEGAL_ENTITY = 'LEGAL_ENTITY';
    /** @var string Плательщик */
    public const PAYER = 'PAYER';
    /** @var string Позиции счета */
    public const ITEMS = 'ITEMS';
    /** @var string Тип НДС */
    public const VAT_TYPE = 'BILL_VAT_TYPE';
    /** @var string Дата оплаты */
    public const PAYMENT_DATE = 'BILL_PAYMENT_DATE';
    /** @var string Комментарий */
    public const COMMENT = 'BILL_COMMENT';
    /** @var string Итоговая сумма к оплате */
    public const PRICE = 'BILL_PRICE';

    /** @var string Код значения "Не облагается НДС" поля Тип НДС */
    public const VAT_EXEMPT = 'vat_exempt';
    /** @var string Код значения "НДС входит в стоимость" поля Тип НДС */
    public const VAT_INCLUDED = 'vat_included';
    /** @var string Код значения "НДС начисляется поверх стоимости" поля Тип НДС */
    public const VAT_NOT_INCLUDED = 'vat_not_included';

    /**
     * @var string Код значения "Оплачен" поля Статус счета
     *
     * @deprecated используйте {@see BillStatusEnumCode} Будет удален в следующих обновлениях
     */
    public const BILL_STATUS_PAID = 'paid';

    /**
     * @var string Код значения "Создан" поля Статус счета
     *
     * @deprecated используйте {@see BillStatusEnumCode} Будет удален в следующих обновлениях
     */
    public const BILL_STATUS_CREATED = 'created';
}
