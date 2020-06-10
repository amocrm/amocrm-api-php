<?php

declare(strict_types=1);

namespace AmoCRM\AmoCRM\Client;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\OAuth\CrmOauthConfigInterface;
use AmoCRM\OAuth\CrmOauthServiceInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * Создание API клиента, может быть полезно в случае работы с несколькими аккаунтами
 * Class AmoCRMApiClientFactory
 *
 * @package AmoCRM\AmoCRM\Client
 */
class AmoCRMApiClientFactory
{
    /** @var CrmOauthConfigInterface */
    protected  $oauthConfig;
    /** @var CrmOauthServiceInterface */
    protected  $oauthService;

    /**
     * AmoCRMApiClientFactory constructor.
     *
     * @param CrmOauthConfigInterface $oauthConfig
     * @param CrmOauthServiceInterface   $oauthService
     */
    public function __construct(CrmOauthConfigInterface $oauthConfig, CrmOauthServiceInterface $oauthService)
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
