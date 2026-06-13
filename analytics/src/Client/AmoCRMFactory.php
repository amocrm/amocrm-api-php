<?php

declare(strict_types=1);

namespace Analytics\Client;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\BaseApiCollection;
use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * Factory for creating configured AmoCRM API client
 */
class AmoCRMFactory
{
    private Config $config;
    private ?AmoCRMApiClient $client = null;
    private TokenStorageInterface $tokenStorage;

    public function __construct(Config $config, TokenStorageInterface $tokenStorage)
    {
        $this->config = $config;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Create configured AmoCRM API client
     */
    public function createClient(): AmoCRMApiClient
    {
        if ($this->client !== null) {
            return $this->client;
        }

        $amocrmConfig = $this->config->getAmoCRMConfig();

        $this->client = new AmoCRMApiClient(
            $amocrmConfig['client_id'],
            $amocrmConfig['client_secret'],
            $amocrmConfig['redirect_uri']
        );

        // Set account base domain
        if (!empty($amocrmConfig['base_domain'])) {
            $this->client->setAccountBaseDomain($amocrmConfig['base_domain']);
        }

        // Load and set access token
        $accessToken = $this->tokenStorage->load();
        if ($accessToken !== null) {
            $this->client->setAccessToken($accessToken);

            // Set callback for token refresh
            $this->client->onAccessTokenRefresh(
                function (AccessTokenInterface $newToken, string $baseDomain) {
                    $this->tokenStorage->save($newToken);
                }
            );
        }

        return $this->client;
    }

    /**
     * Get service by name
     *
     * @return BaseApiCollection|object
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    public function getService(string $name): object
    {
        $client = $this->createClient();

        return match ($name) {
            'leads' => $client->leads(),
            'contacts' => $client->contacts(),
            'companies' => $client->companies(),
            'customers' => $client->customers(),
            'tasks' => $client->tasks(),
            'events' => $client->events(),
            'unsorted' => $client->unsorted(),
            'webhooks' => $client->webhooks(),
            'account' => $client->account(),
            'pipelines' => $client->pipelines(),
            'sources' => $client->sources(),
            'users' => $client->users(),
            'files' => $client->files(),
            'currencies' => $client->currencies(),
            'catalogs' => $client->catalogs(),
            default => throw new \InvalidArgumentException("Unknown service: {$name}"),
        };
    }

    /**
     * Check if client is configured with valid credentials
     */
    public function isConfigured(): bool
    {
        $amocrmConfig = $this->config->getAmoCRMConfig();

        return !empty($amocrmConfig['client_id'])
            && !empty($amocrmConfig['client_secret'])
            && !empty($amocrmConfig['base_domain']);
    }

    /**
     * Get OAuth authorization URL
     */
    public function getAuthorizationUrl(string $state = ''): string
    {
        $client = $this->createClient();

        return $client->getOAuthClient()->getAuthorizeUrl([
            'state' => $state,
        ]);
    }

    /**
     * Exchange authorization code for access token
     */
    public function exchangeCode(string $code): AccessTokenInterface
    {
        $client = $this->createClient();
        $accessToken = $client->getOAuthClient()->getAccessTokenByCode($code);

        // Save token
        $this->tokenStorage->save($accessToken);

        return $accessToken;
    }
}
