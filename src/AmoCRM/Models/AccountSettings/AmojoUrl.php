<?php

namespace AmoCRM\Models\AccountSettings;

use Illuminate\Contracts\Support\Arrayable;

class AmojoUrl implements Arrayable
{

    /**
     * @var string
     */
    protected $baseUrl;

    public function __construct(
        string $baseUrl
    ) {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'base_url' => $this->baseUrl,
        ];
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}
