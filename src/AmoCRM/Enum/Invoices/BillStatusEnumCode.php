<?php

declare(strict_types=1);

namespace AmoCRM\Enum\Invoices;

use AmoCRM\Helpers\EntityTypesInterface;

/**
 * Статусы поля "Статус счета" каталога "Счета/Покупки" {@see EntityTypesInterface::INVOICES_CATALOG_TYPE_STRING}
 */
final class BillStatusEnumCode
{
    /** @var string Создан */
    public const CREATED = 'created';
    /** @var string Оплачен */
    public const PAID = 'paid';
    /** @var string Оплачен в аванс */
    public const PAID_IN_ADVANCE = 'paid_in_advance';
    /** @var string Частично оплачен */
    public const PARTIALLY_PAID = 'partially_paid';
}
