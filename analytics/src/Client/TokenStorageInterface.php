<?php

declare(strict_types=1);

namespace Analytics\Client;

use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * Interface for token storage implementations
 */
interface TokenStorageInterface
{
    /**
     * Load access token from storage
     */
    public function load(): ?AccessTokenInterface;

    /**
     * Save access token to storage
     */
    public function save(AccessTokenInterface $token): void;

    /**
     * Check if token exists in storage
     */
    public function exists(): bool;

    /**
     * Delete token from storage
     */
    public function delete(): void;
}
