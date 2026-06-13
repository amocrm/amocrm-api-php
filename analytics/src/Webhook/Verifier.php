<?php

declare(strict_types=1);

namespace Analytics\Webhook;

/**
 * Webhook signature verification with HMAC-SHA256
 */
class Verifier
{
    private string $clientSecret;

    public function __construct(string $clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Verify webhook signature from amoCRM
     *
     * @param string $payload Raw request body
     * @param string $signature Signature from X-Signature header
     * @return bool True if signature is valid
     */
    public function verify(string $payload, string $signature): bool
    {
        // Reject empty signatures or missing secret
        if (empty($signature) || empty($this->clientSecret)) {
            return false;
        }

        $expected = $this->calculateSignature($payload);

        // Use timing-safe comparison to prevent timing attacks
        return hash_equals($expected, $signature);
    }

    /**
     * Calculate expected HMAC-SHA256 signature
     */
    public function calculateSignature(string $payload): string
    {
        return hash_hmac('sha256', $payload, $this->clientSecret);
    }

    /**
     * Generate signature for testing purposes
     */
    public static function generateSignature(string $payload, string $clientSecret): string
    {
        return hash_hmac('sha256', $payload, $clientSecret);
    }
}
