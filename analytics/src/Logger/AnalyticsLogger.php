<?php

declare(strict_types=1);

namespace Analytics\Logger;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\PsrLogMessageProcessor;

/**
 * Logger for analytics operations
 */
class AnalyticsLogger
{
    private Logger $logger;
    private string $logPath;

    public function __construct(string $logPath, string $level = 'info')
    {
        $this->logPath = rtrim($logPath, '/');

        if (!is_dir($this->logPath)) {
            mkdir($this->logPath, 0755, true);
        }

        $this->logger = new Logger('analytics');

        // Add processors
        $this->logger->pushProcessor(new UidProcessor());
        $this->logger->pushProcessor(new PsrLogMessageProcessor());

        // Add rotating file handler
        $this->logger->pushHandler(
            new RotatingFileHandler(
                $this->logPath . '/analytics.log',
                14, // max files
                $this->parseLevel($level)
            )
        );

        // Also log to stdout for CLI
        $this->logger->pushHandler(
            new StreamHandler('php://stdout', $this->parseLevel($level))
        );
    }

    public function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * Log ETL operation
     */
    public function etl(string $entity, string $action, array $context = []): void
    {
        $this->logger->info("ETL: {$entity} - {$action}", $context);
    }

    /**
     * Log webhook received
     */
    public function webhook(string $eventType, string $entityId, array $context = []): void
    {
        $this->logger->info("Webhook: {$eventType} - {$entityId}", $context);
    }

    /**
     * Log API error
     */
    public function apiError(string $message, array $context = []): void
    {
        $this->logger->error("API Error: {$message}", $context);
    }

    /**
     * Log rate limit hit
     */
    public function rateLimit(int $retryAfter, array $context = []): void
    {
        $this->logger->warning("Rate limit hit, retry after {$retryAfter}s", $context);
    }

    /**
     * Log performance metric
     */
    public function performance(string $operation, float $duration, array $context = []): void
    {
        $this->logger->info("Performance: {$operation} took {$duration}s", array_merge(
            ['duration_seconds' => $duration],
            $context
        ));
    }

    /**
     * Log sync state
     */
    public function sync(string $entity, int $processed, int $total, array $context = []): void
    {
        $this->logger->info("Sync: {$entity} - {$processed}/{$total}", array_merge(
            ['processed' => $processed, 'total' => $total, 'progress' => $total > 0 ? round($processed / $total * 100, 2) : 0],
            $context
        ));
    }

    private function parseLevel(string $level): Level
    {
        return match (strtolower($level)) {
            'debug' => Level::Debug,
            'info' => Level::Info,
            'notice' => Level::Notice,
            'warning', 'warn' => Level::Warning,
            'error' => Level::Error,
            'critical' => Level::Critical,
            'alert' => Level::Alert,
            'emergency' => Level::Emergency,
            default => Level::Info,
        };
    }
}
