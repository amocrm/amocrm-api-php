<?php

declare(strict_types=1);

namespace AmoCRM\Models;

/**
 * @since Release Spring 2022
 */
class CurrencyModel extends BaseApiModel
{
    /**
     * @var string Код валюты
     */
    protected $code;

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param array $currencyArr
     *
     * @return static
     */
    public static function fromArray(array $currencyArr): self
    {
        $currency = new self();
        $currency->setCode($currencyArr['code']);

        return $currency;
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
        ];
    }

    /**
     * @param string|null $requestId
     *
     * @return string[]
     */
    public function toApi(string $requestId = null): array
    {
        return $this->toArray();
    }
}
