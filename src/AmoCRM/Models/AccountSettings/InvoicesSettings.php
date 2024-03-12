<?php

namespace AmoCRM\Models\AccountSettings;

use AmoCRM\Contracts\Support\Arrayable;

class InvoicesSettings implements Arrayable
{
    /**
     * @var string|null
     */
    protected $lang;

    /**
     * @var int|null
     */
    protected $invoicesCatalogId;

    public function __construct(
        ?string $lang,
        ?int $invoicesCatalogId
    ) {
        $this->lang = $lang;
        $this->invoicesCatalogId = $invoicesCatalogId;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'lang' => $this->getLang(),
            'invoices_catalog_id' => $this->getInvoicesCatalogId(),
        ];
    }

    /**
     * @return string|null
     */
    public function getLang(): ?string
    {
        return $this->lang;
    }

    /**
     * @return int|null
     */
    public function getInvoicesCatalogId(): ?int
    {
        return $this->invoicesCatalogId;
    }
}
