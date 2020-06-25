<?php

declare(strict_types=1);

namespace AmoCRM\AmoCRM\Client;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\OAuth\OAuthConfigInterface;
use AmoCRM\OAuth\OAuthServiceInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * Создание API клиента, может быть полезно в случае работы с несколькими аккаунтами
 * Class AmoCRMApiClientFactory
 *
 * @package AmoCRM\AmoCRM\Client
 */
class AmoCRMApiClientFactory
{
    /** @var OAuthConfigInterface */
    protected $oAuthConfig;
    /** @var OAuthServiceInterface */
    protected $oAuthService;

    /**
     * AmoCRMApiClientFactory constructor.
     *
     * @param OAuthConfigInterface  $oAuthConfig
     * @param OAuthServiceInterface $oAuthService
     */
    public function __construct(OAuthConfigInterface $oAuthConfig, OAuthServiceInterface $oAuthService)
    {
        $this->oAuthConfig  = $oAuthConfig;
        $this->oAuthService = $oAuthService;
    }

    /**
     * @return AmoCRMApiClient
     */
    public function make(): AmoCRMApiClient
    {
        $client = new AmoCRMApiClient(
            $this->oAuthConfig->getIntegrationId(),
            $this->oAuthConfig->getSecretKey(),
            $this->oAuthConfig->getRedirectDomain()
        );

        $client->onAccessTokenRefresh(
            function (AccessTokenInterface $accessToken, string $baseDomain) {
                $this->oAuthService->saveOAuthToken($accessToken, $baseDomain);
            }
        );

        return $client;
    }
}
