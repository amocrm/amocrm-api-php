<?php

declare(strict_types=0);

namespace AmoCRM\Client;

use AmoCRM\Exceptions\InvalidArgumentException;
use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use League\OAuth2\Client\Token\AccessToken;
use Throwable;

/**
 * Class LongLivedAccessToken
 *
 * @package AmoCRM\Client
 */
class LongLivedAccessToken extends AccessToken
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $accessToken)
    {
        try {
            $parsedAccessToken = Configuration::forUnsecuredSigner()->parser()->parse($accessToken);
        } catch (Throwable $e) {
            throw new InvalidArgumentException(
                'Error parsing given access token. Prev error: ' . $e->getMessage(),
                0,
                [],
                'Check access token.'
            );
        }

        $claims = $parsedAccessToken->claims();

        /** @var DateTimeImmutable $expiresAt */
        $expiresAt = $claims->get('exp');

        $options = [
            'expires' => $expiresAt->getTimestamp(),
            'access_token' => $accessToken,
        ];

        parent::__construct($options);
    }
}
