<?php

declare(strict_types=1);

namespace Analytics\Repository;

use Analytics\Models\PipelineStages;
use Doctrine\DBAL\Connection;

/**
 * Repository for pipeline stages
 */
class PipelineStagesRepo
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Upsert pipeline stage
     */
    public function upsert(array $data): int
    {
        $sql = <<<SQL
        INSERT INTO pipeline_stages (
            pipeline_id, pipeline_name, status_id, status_name,
            sort_order, is_final, final_type
        ) VALUES (
            :pipeline_id, :pipeline_name, :status_id, :status_name,
            :sort_order, :is_final, :final_type
        )
        ON CONFLICT (pipeline_id, status_id) DO UPDATE SET
            pipeline_name = EXCLUDED.pipeline_name,
            status_name = EXCLUDED.status_name,
            sort_order = EXCLUDED.sort_order,
            is_final = EXCLUDED.is_final,
            final_type = EXCLUDED.final_type,
            updated_at = NOW()
        RETURNING id
        SQL;

        $result = $this->db->executeQuery($sql, [
            'pipeline_id' => $data['pipeline_id'],
            'pipeline_name' => $data['pipeline_name'],
            'status_id' => $data['status_id'],
            'status_name' => $data['status_name'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_final' => $data['is_final'] ?? false,
            'final_type' => $data['final_type'] ?? null,
        ]);

        return (int)$result->fetchOne();
    }

    /**
     * Get all stages for a pipeline
     */
    public function findByPipelineId(int $pipelineId): array
    {
        $sql = <<<SQL
        SELECT * FROM pipeline_stages
        WHERE pipeline_id = :pipeline_id
        ORDER BY sort_order ASC
        SQL;

        return $this->db->fetchAllAssociative($sql, ['pipeline_id' => $pipelineId]);
    }

    /**
     * Get all pipelines
     */
    public function findAll(): array
    {
        $sql = "SELECT DISTINCT pipeline_id, pipeline_name FROM pipeline_stages ORDER BY pipeline_id";
        return $this->db->fetchAllAssociative($sql);
    }
}
