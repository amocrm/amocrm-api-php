<?php

declare(strict_types=1);

namespace Analytics\Config;

/**
 * Application configuration
 */
class Config
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public static function fromEnv(): self
    {
        $config = [
            'app' => [
                'env' => $_ENV['APP_ENV'] ?? 'production',
                'debug' => (bool)($_ENV['APP_DEBUG'] ?? false),
                'timezone' => $_ENV['APP_TIMEZONE'] ?? 'UTC',
            ],
            'database' => [
                'driver' => 'pdo_pgsql',
                'host' => $_ENV['DB_HOST'] ?? 'localhost',
                'port' => (int)($_ENV['DB_PORT'] ?? 5432),
                'dbname' => $_ENV['DB_NAME'] ?? 'amocrm_analytics',
                'user' => $_ENV['DB_USER'] ?? 'postgres',
                'password' => $_ENV['DB_PASSWORD'] ?? '',
            ],
            'amocrm' => [
                'client_id' => $_ENV['AMOCRM_CLIENT_ID'] ?? '',
                'client_secret' => $_ENV['AMOCRM_CLIENT_SECRET'] ?? '',
                'redirect_uri' => $_ENV['AMOCRM_REDIRECT_URI'] ?? '',
                'base_domain' => $_ENV['AMOCRM_BASE_DOMAIN'] ?? '',
            ],
            'fusio' => [
                'app_public' => $_ENV['FUSIO_PUBLIC_URL'] ?? '',
                'app_secret' => $_ENV['FUSIO_APP_SECRET'] ?? '',
            ],
            'analytics' => [
                'batch_size' => (int)($_ENV['ANALYTICS_BATCH_SIZE'] ?? 100),
                'webhook_timeout' => (int)($_ENV['ANALYTICS_WEBHOOK_TIMEOUT'] ?? 5),
                'max_retries' => (int)($_ENV['ANALYTICS_MAX_RETRIES'] ?? 3),
            ],
            'logging' => [
                'path' => $_ENV['LOG_PATH'] ?? 'var/log',
                'level' => $_ENV['LOG_LEVEL'] ?? 'info',
            ],
            'token_storage' => [
                'path' => $_ENV['TOKEN_STORAGE_PATH'] ?? 'var/tokens',
            ],
        ];

        return new self($config);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $k) {
            if (!is_array($value) || !array_key_exists($k, $value)) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    public function getDatabaseDsn(): string
    {
        $db = $this->config['database'];
        return sprintf(
            'pgsql:host=%s;port=%d;dbname=%s',
            $db['host'],
            $db['port'],
            $db['dbname']
        );
    }

    public function getDatabaseConfig(): array
    {
        return $this->config['database'];
    }

    public function getAmoCRMConfig(): array
    {
        return $this->config['amocrm'];
    }

    public function isDebug(): bool
    {
        return $this->config['app']['debug'];
    }

    public function getTimezone(): string
    {
        return $this->config['app']['timezone'];
    }
}
