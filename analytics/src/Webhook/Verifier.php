<?php

declare(strict_types=1);

namespace Analytics\Webhook;

/**
 * Verifies webhook signatures from amoCRM
 */
class Verifier
{
    private string $clientSecret;

    public function __construct(string $clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Verify webhook signature
     */
    public function verify(string $payload, string $signature): bool
    {
        if (empty($signature) || empty($this->clientSecret)) {
            return false;
        }

        $expected = $this->calculateSignature($payload);

        return hash_equals($expected, $signature);
    }

    /**
     * Calculate expected signature
     */
    public function calculateSignature(string $payload): string
    {
        return hash_hmac('sha256', $payload, $this->clientSecret);
    }

    /**
     * Generate signature for testing
     */
    public static function generateSignature(string $payload, string $clientSecret): string
    {
        return hash_hmac('sha256', $payload, $clientSecret);
    }
}
