<?php

declare(strict_types=1);

namespace AmoCRM\OAuth;

/**
 * Интерфейс для настроек Oauth клиента
 * Interface CrmOauthConfigInterface
 *
 * @package AmoCRM\OAuth
 */
interface OAuthConfigInterface
{
    /**
     * @return string
     */
    public function getIntegrationId(): string;

    /**
     * @return string
     */
    public function getSecretKey(): string;

    /**
     * @return string
     */
    public function getRedirectDomain(): string;
}
