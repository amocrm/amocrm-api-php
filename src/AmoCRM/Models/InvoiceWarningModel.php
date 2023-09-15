<?php

namespace AmoCRM\Models;

class InvoiceWarningModel extends BaseApiModel
{
    /**
     * @var string|null
     */
    protected $message;

    public function __construct(?string $message)
    {
        $this->message = $message;
    }

    public function getInvoiceWarningMessage(): ?string
    {
        return $this->message;
    }

    public static function fromArray(array $value): self
    {
        return new self($value['message']);
    }

    public function toArray(): array
    {
        return [
            'message' => $this->getInvoiceWarningMessage(),
        ];
    }

    public function toApi(string $requestId = null): array
    {
        return $this->toArray();
    }
}
