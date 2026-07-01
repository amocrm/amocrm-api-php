<?php

declare(strict_types=1);

namespace Analytics\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Analytics\Config\Config;

/**
 * Factory for creating database connections
 */
class ConnectionFactory
{
    private ?Connection $connection = null;
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Get database connection
     */
    public function getConnection(): Connection
    {
        if ($this->connection !== null) {
            return $this->connection;
        }

        $dbConfig = $this->config->getDatabaseConfig();

        $connectionParams = [
            'driver' => 'pdo_pgsql',
            'host' => $dbConfig['host'],
            'port' => $dbConfig['port'],
            'dbname' => $dbConfig['dbname'],
            'user' => $dbConfig['user'],
            'password' => $dbConfig['password'],
            'charset' => 'utf8',
        ];

        $this->connection = DriverManager::getConnection($connectionParams);

        return $this->connection;
    }

    /**
     * Close connection
     */
    public function close(): void
    {
        if ($this->connection !== null) {
            $this->connection->close();
            $this->connection = null;
        }
    }

    /**
     * Check if database is accessible
     */
    public function isConnected(): bool
    {
        try {
            $this->getConnection()->executeQuery('SELECT 1');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
