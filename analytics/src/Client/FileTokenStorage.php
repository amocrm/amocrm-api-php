<?php

declare(strict_types=1);

namespace Analytics\Client;

use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * File-based token storage
 */
class FileTokenStorage implements TokenStorageInterface
{
    private string $storagePath;

    public function __construct(string $storagePath)
    {
        $this->storagePath = rtrim($storagePath, '/');

        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }
    }

    /**
     * @inheritDoc
     */
    public function load(): ?AccessTokenInterface
    {
        $file = $this->getTokenFile();

        if (!file_exists($file)) {
            return null;
        }

        $data = json_decode(file_get_contents($file), true);

        if ($data === null || !isset($data['access_token'])) {
            return null;
        }

        return new AccessToken($data);
    }

    /**
     * @inheritDoc
     */
    public function save(AccessTokenInterface $token): void
    {
        $file = $this->getTokenFile();
        $data = $token->jsonSerialize();

        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * @inheritDoc
     */
    public function exists(): bool
    {
        return file_exists($this->getTokenFile());
    }

    /**
     * @inheritDoc
     */
    public function delete(): void
    {
        $file = $this->getTokenFile();

        if (file_exists($file)) {
            unlink($file);
        }
    }

    private function getTokenFile(): string
    {
        return $this->storagePath . '/amocrm_token.json';
    }
}
