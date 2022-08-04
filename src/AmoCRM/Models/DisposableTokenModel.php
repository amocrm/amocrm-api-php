<?php

declare(strict_types=1);

namespace AmoCRM\Models;

use DateTimeImmutable;
use Lcobucci\JWT\Token;

/**
 * Class DisposableTokenModel
 *
 * @package AmoCRM\Models
 */
class DisposableTokenModel extends BaseApiModel
{
    /** @var string */
    protected $tokenUuid;

    /** @var string */
    protected $clientUuid;

    /** @var string */
    protected $accountDomain;

    /** @var string */
    protected $accountSubdomain;

    /** @var int */
    protected $accountId;

    /** @var int */
    protected $userId;

    /** @var bool */
    protected $isAdmin;

    /** @var int */
    protected $expiresAt;

    /**
     * @param string $tokenUuid
     *
     * @return $this
     */
    public function setTokenUuid(string $tokenUuid): self
    {
        $this->tokenUuid = $tokenUuid;

        return $this;
    }

    /**
     * @return string
     */
    public function getTokenUuid(): string
    {
        return $this->tokenUuid;
    }

    /**
     * @param string $clientUuid
     *
     * @return $this
     */
    public function setClientUuid(string $clientUuid): self
    {
        $this->clientUuid = $clientUuid;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientUuid(): string
    {
        return $this->clientUuid;
    }

    /**
     * @param string $accountDomain
     *
     * @return $this
     */
    public function setAccountDomain(string $accountDomain): self
    {
        $this->accountDomain = $accountDomain;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountDomain(): string
    {
        return $this->accountDomain;
    }

    /**
     * @param string $accountSubdomain
     *
     * @return $this
     */
    public function setAccountSubdomain(string $accountSubdomain): self
    {
        $this->accountSubdomain = $accountSubdomain;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountSubdomain(): string
    {
        return $this->accountSubdomain;
    }

    /**
     * @param int $accountId
     *
     * @return $this
     */
    public function setAccountId(int $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->accountId;
    }

    /**
     * @param int $userId
     *
     * @return $this
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     * @return DisposableTokenModel
     */
    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpiresAt(): int
    {
        return $this->expiresAt;
    }

    /**
     * @param int $expiresAt
     *
     * @return DisposableTokenModel
     */
    public function setExpiresAt(int $expiresAt): DisposableTokenModel
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }


    /**
     * @param Token $jwtToken
     *
     * @return static
     */
    public static function fromJwtToken(Token $jwtToken): self
    {
        $disposableToken = new self();
        $claims = $jwtToken->claims();
        /** @var DateTimeImmutable $expiresAt */
        $expiresAt = $claims->get('exp');
        $disposableToken->setTokenUuid($claims->get('jti'))
            ->setClientUuid($claims->get('client_uuid'))
            ->setAccountDomain($claims->get('iss'))
            ->setAccountSubdomain($claims->get('subdomain'))
            ->setAccountId((int)$claims->get('account_id'))
            ->setUserId((int)$claims->get('user_id'))
            ->setIsAdmin((bool)$claims->get('is_admin'))
            ->setExpiresAt($expiresAt->getTimestamp());

        return $disposableToken;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'token_uuid'        => $this->getTokenUuid(),
            'client_uuid'       => $this->getClientUuid(),
            'account_domain'    => $this->getAccountDomain(),
            'account_subdomain' => $this->getAccountSubdomain(),
            'account_id'        => $this->getAccountId(),
            'user_id'           => $this->getUserId(),
            'is_admin'          => $this->isAdmin(),
            'expires_at'        => $this->getExpiresAt(),
        ];
    }

    /**
     * @param string|null $requestId
     *
     * @return array
     */
    public function toApi(string $requestId = null): array
    {
        return $this->toArray();
    }
}
