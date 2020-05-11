<?php

namespace AmoCRM\Models\AccountSettings;

use Illuminate\Contracts\Support\Arrayable;

class NotificationsInfo implements Arrayable
{
    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $wsUrl;

    /**
     * @var string
     */
    protected $wsUrlV2;

    public function __construct(
        string $baseUrl,
        string $wsUrl,
        string $wsUrlV2
    ) {
        $this->baseUrl = $baseUrl;
        $this->wsUrl = $wsUrl;
        $this->wsUrlV2 = $wsUrlV2;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'base_url' => $this->baseUrl,
            'ws_url' => $this->wsUrl,
            'ws_url_v2' => $this->wsUrlV2,
        ];
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @return string
     */
    public function getWsUrl(): string
    {
        return $this->wsUrl;
    }

    /**
     * @return string
     */
    public function getWsUrlV2(): string
    {
        return $this->wsUrlV2;
    }
}
