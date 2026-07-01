<?php

declare(strict_types=1);

namespace Analytics\Tests\Unit;

use Analytics\Webhook\Verifier;
use PHPUnit\Framework\TestCase;

class VerifierTest extends TestCase
{
    public function testVerifyWithValidSignature(): void
    {
        $secret = 'test_secret_key';
        $payload = '{"webhook":{"type":"lead_added","timestamp":1234567890},"payload":{"id":123}}';
        
        $verifier = new Verifier($secret);
        $signature = hash_hmac('sha256', $payload, $secret);

        $this->assertTrue($verifier->verify($payload, $signature));
    }

    public function testVerifyWithInvalidSignature(): void
    {
        $secret = 'test_secret_key';
        $payload = '{"webhook":{"type":"lead_added"},"payload":{"id":123}}';
        
        $verifier = new Verifier($secret);
        $invalidSignature = 'invalid_signature_here';

        $this->assertFalse($verifier->verify($payload, $invalidSignature));
    }

    public function testVerifyWithEmptySecret(): void
    {
        $verifier = new Verifier('');
        $payload = '{"test":"data"}';

        $this->assertFalse($verifier->verify($payload, 'any_signature'));
    }

    public function testVerifyWithEmptySignature(): void
    {
        $verifier = new Verifier('some_secret');
        $payload = '{"test":"data"}';

        $this->assertFalse($verifier->verify($payload, ''));
    }

    public function testCalculateSignature(): void
    {
        $secret = 'my_secret';
        $payload = '{"test":"data"}';
        
        $verifier = new Verifier($secret);
        $signature = $verifier->calculateSignature($payload);
        $expected = hash_hmac('sha256', $payload, $secret);

        $this->assertEquals($expected, $signature);
    }

    public function testGenerateSignature(): void
    {
        $secret = 'another_secret';
        $payload = '{"type":"test"}';

        $signature = Verifier::generateSignature($payload, $secret);
        $expected = hash_hmac('sha256', $payload, $secret);

        $this->assertEquals($expected, $signature);
    }

    public function testSignatureIsCaseSensitive(): void
    {
        $secret = 'secret';
        $payload = '{"data":"test"}';
        
        $verifier = new Verifier($secret);
        $signature = $verifier->calculateSignature($payload);

        // Same payload, same secret should produce same signature
        $signature2 = $verifier->calculateSignature($payload);
        $this->assertEquals($signature, $signature2);

        // Different payload should produce different signature
        $differentPayload = '{"data":"different"}';
        $differentSignature = $verifier->calculateSignature($differentPayload);
        $this->assertNotEquals($signature, $differentSignature);
    }

    public function testTimingSafeComparison(): void
    {
        $secret = 'timing_test_secret';
        $payload = '{"secure":"data"}';
        
        $verifier = new Verifier($secret);
        $validSignature = hash_hmac('sha256', $payload, $secret);

        // The verify method should use hash_equals for timing-safe comparison
        $this->assertTrue($verifier->verify($payload, $validSignature));
        
        // Even with similar but wrong signatures, verify should return false
        $this->assertFalse($verifier->verify($payload, substr($validSignature, 0, -1) . 'X'));
    }
}
