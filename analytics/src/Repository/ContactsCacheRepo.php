<?php

declare(strict_types=1);

namespace Analytics\Repository;

use Carbon\Carbon;
use Doctrine\DBAL\Connection;

/**
 * Repository for contacts cache
 */
class ContactsCacheRepo
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Upsert contact cache
     */
    public function upsert(array $data): int
    {
        $sql = <<<SQL
        INSERT INTO contacts_cache (
            contact_id, name, first_name, last_name,
            email, phone, custom_fields,
            created_at, updated_at, synced_at
        ) VALUES (
            :contact_id, :name, :first_name, :last_name,
            :email, :phone, :custom_fields,
            :created_at, :updated_at, :synced_at
        )
        ON CONFLICT (contact_id) DO UPDATE SET
            name = EXCLUDED.name,
            first_name = EXCLUDED.first_name,
            last_name = EXCLUDED.last_name,
            email = EXCLUDED.email,
            phone = EXCLUDED.phone,
            custom_fields = EXCLUDED.custom_fields,
            updated_at = COALESCE(EXCLUDED.updated_at, contacts_cache.updated_at),
            synced_at = EXCLUDED.synced_at
        RETURNING id
        SQL;

        $result = $this->db->executeQuery($sql, [
            'contact_id' => $data['contact_id'],
            'name' => $data['name'] ?? null,
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'custom_fields' => json_encode($data['custom_fields'] ?? []),
            'created_at' => $data['created_at'] ?? null,
            'updated_at' => $data['updated_at'] ?? null,
            'synced_at' => $data['synced_at'] ?? Carbon::now()->toDateTimeString(),
        ]);

        return (int)$result->fetchOne();
    }

    /**
     * Find by contact ID
     */
    public function findById(int $contactId): ?array
    {
        $sql = 'SELECT * FROM contacts_cache WHERE contact_id = :contact_id';
        $row = $this->db->fetchAssociative($sql, ['contact_id' => $contactId]);

        if ($row === false) {
            return null;
        }

        $row['custom_fields'] = json_decode($row['custom_fields'] ?? '{}', true);
        return $row;
    }

    /**
     * Find by email
     */
    public function findByEmail(string $email): ?array
    {
        $sql = 'SELECT * FROM contacts_cache WHERE email = :email';
        $row = $this->db->fetchAssociative($sql, ['email' => $email]);

        if ($row === false) {
            return null;
        }

        $row['custom_fields'] = json_decode($row['custom_fields'] ?? '{}', true);
        return $row;
    }

    /**
     * Search by name
     */
    public function searchByName(string $query, int $limit = 20): array
    {
        $sql = <<<SQL
        SELECT * FROM contacts_cache
        WHERE name ILIKE :query OR first_name ILIKE :query OR last_name ILIKE :query
        ORDER BY updated_at DESC
        LIMIT :limit
        SQL;

        $rows = $this->db->fetchAllAssociative($sql, [
            'query' => '%' . $query . '%',
            'limit' => $limit,
        ]);

        return array_map(function ($row) {
            $row['custom_fields'] = json_decode($row['custom_fields'] ?? '{}', true);
            return $row;
        }, $rows);
    }

    /**
     * Get contacts updated since
     */
    public function getUpdatedSince(Carbon $since, int $limit = 100): array
    {
        $sql = <<<SQL
        SELECT * FROM contacts_cache
        WHERE updated_at >= :since
        ORDER BY updated_at ASC
        LIMIT :limit
        SQL;

        $rows = $this->db->fetchAllAssociative($sql, [
            'since' => $since->toDateTimeString(),
            'limit' => $limit,
        ]);

        return array_map(function ($row) {
            $row['custom_fields'] = json_decode($row['custom_fields'] ?? '{}', true);
            return $row;
        }, $rows);
    }

    /**
     * Delete old cached contacts
     */
    public function deleteOlderThan(Carbon $date): int
    {
        $sql = <<<SQL
        DELETE FROM contacts_cache
        WHERE synced_at < :date
        AND contact_id NOT IN (
            SELECT DISTINCT contact_ids->>0
            FROM lead_snapshots
            WHERE contact_ids IS NOT NULL
            AND updated_at > :date
        )
        SQL;

        return $this->db->executeStatement($sql, ['date' => $date->toDateTimeString()]);
    }
}
