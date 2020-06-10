<?php

declare(strict_types=1);

namespace AmoCRM\AmoCRM\Client;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\OAuth\OauthConfigInterface;
use AmoCRM\OAuth\OauthServiceInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * Создание API клиента, может быть полезно в случае работы с несколькими аккаунтами
 * Class AmoCRMApiClientFactory
 *
 * @package AmoCRM\AmoCRM\Client
 */
class AmoCRMApiClientFactory
{
    /** @var OauthConfigInterface */
    protected  $oauthConfig;
    /** @var OauthServiceInterface */
    protected  $oauthService;

    /**
     * AmoCRMApiClientFactory constructor.
     *
     * @param OauthConfigInterface  $oauthConfig
     * @param OauthServiceInterface $oauthService
     */
    public function __construct(OauthConfigInterface $oauthConfig, OauthServiceInterface $oauthService)
    {
        $this->oauthConfig  = $oauthConfig;
        $this->oauthService = $oauthService;
    }

    /**
     * @return AmoCRMApiClient
     */
    public function make(): AmoCRMApiClient
    {
        $client = new AmoCRMApiClient(
            $this->oauthConfig->getIntegrationId(),
            $this->oauthConfig->getSecretKey(),
            $this->oauthConfig->getRedirectDomain()
        );

        $client->onAccessTokenRefresh(
            function (AccessTokenInterface $accessToken, string $baseDomain) {
                $this->oauthService->saveOauthToken($accessToken, $baseDomain);
            }
        );

        return $client;
    }
}
