<?php

declare(strict_types=1);

namespace AmoCRM\OAuth;

use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * Интерфейс сервиса, который может сохранять oauth токены
 * Interface CrmOauthServiceInterface
 *
 * @package AmoCRM\OAuth
 */
interface OAuthServiceInterface
{
    /**
     * @param AccessTokenInterface $accessToken
     * @param string               $baseDomain
     */
    public function saveOAuthToken(AccessTokenInterface $accessToken, string $baseDomain): void;
}
