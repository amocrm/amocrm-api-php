<?php

namespace AmoCRM\Analytics\Models;

use AmoCRM\Analytics\BaseAnalyticsModel;
use AmoCRM\Models\EventModel;

/**
 * Class LeadStatusHistoryModel
 *
 * Модель для хранения истории переходов сделок по статусам.
 * Используется для аналитики времени нахождения на каждом этапе воронки.
 *
 * @package AmoCRM\Analytics\Models
 */
class LeadStatusHistoryModel extends BaseAnalyticsModel
{
    /**
     * ID записи истории
     * @var int|null
     */
    protected $id;

    /**
     * ID сделки
     * @var int|null
     */
    protected $leadId;

    /**
     * ID статуса ДО изменения
     * @var int|null
     */
    protected $statusIdFrom;

    /**
     * ID статуса ПОСЛЕ изменения
     * @var int|null
     */
    protected $statusIdTo;

    /**
     * ID воронки
     * @var int|null
     */
    protected $pipelineId;

    /**
     * ID пользователя, совершившего изменение
     * @var int|null
     */
    protected $changedBy;

    /**
     * Время изменения (Unix timestamp)
     * @var int|null
     */
    protected $changedAt;

    /**
     * Продолжительность нахождения в предыдущем статусе (секунды)
     * @var int|null
     */
    protected $durationSeconds;

    /**
     * Предыдущий статус (для отображения)
     * @var string|null
     */
    protected $statusFromName;

    /**
     * Новый статус (для отображения)
     * @var string|null
     */
    protected $statusToName;

    /**
     * Флаг перехода в победу
     * @var bool
     */
    protected $isWonTransition = false;

    /**
     * Флаг перехода в проигрыш
     * @var bool
     */
    protected $isLostTransition = false;

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return self
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLeadId(): ?int
    {
        return $this->leadId;
    }

    /**
     * @param int|null $leadId
     * @return self
     */
    public function setLeadId(?int $leadId): self
    {
        $this->leadId = $leadId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getStatusIdFrom(): ?int
    {
        return $this->statusIdFrom;
    }

    /**
     * @param int|null $statusIdFrom
     * @return self
     */
    public function setStatusIdFrom(?int $statusIdFrom): self
    {
        $this->statusIdFrom = $statusIdFrom;
        $this->updateTransitionFlags();
        return $this;
    }

    /**
     * @return int|null
     */
    public function getStatusIdTo(): ?int
    {
        return $this->statusIdTo;
    }

    /**
     * @param int|null $statusIdTo
     * @return self
     */
    public function setStatusIdTo(?int $statusIdTo): self
    {
        $this->statusIdTo = $statusIdTo;
        $this->updateTransitionFlags();
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPipelineId(): ?int
    {
        return $this->pipelineId;
    }

    /**
     * @param int|null $pipelineId
     * @return self
     */
    public function setPipelineId(?int $pipelineId): self
    {
        $this->pipelineId = $pipelineId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getChangedBy(): ?int
    {
        return $this->changedBy;
    }

    /**
     * @param int|null $changedBy
     * @return self
     */
    public function setChangedBy(?int $changedBy): self
    {
        $this->changedBy = $changedBy;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getChangedAt(): ?int
    {
        return $this->changedAt;
    }

    /**
     * @param int|null $changedAt
     * @return self
     */
    public function setChangedAt(?int $changedAt): self
    {
        $this->changedAt = $changedAt;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDurationSeconds(): ?int
    {
        return $this->durationSeconds;
    }

    /**
     * @param int|null $durationSeconds
     * @return self
     */
    public function setDurationSeconds(?int $durationSeconds): self
    {
        $this->durationSeconds = $durationSeconds;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatusFromName(): ?string
    {
        return $this->statusFromName;
    }

    /**
     * @param string|null $statusFromName
     * @return self
     */
    public function setStatusFromName(?string $statusFromName): self
    {
        $this->statusFromName = $statusFromName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatusToName(): ?string
    {
        return $this->statusToName;
    }

    /**
     * @param string|null $statusToName
     * @return self
     */
    public function setStatusToName(?string $statusToName): self
    {
        $this->statusToName = $statusToName;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWonTransition(): bool
    {
        return $this->isWonTransition;
    }

    /**
     * @param bool $isWonTransition
     * @return self
     */
    public function setIsWonTransition(bool $isWonTransition): self
    {
        $this->isWonTransition = $isWonTransition;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLostTransition(): bool
    {
        return $this->isLostTransition;
    }

    /**
     * @param bool $isLostTransition
     * @return self
     */
    public function setIsLostTransition(bool $isLostTransition): self
    {
        $this->isLostTransition = $isLostTransition;
        return $this;
    }

    /**
     * Обновить флаги переходов в победу/проигрыш
     */
    protected function updateTransitionFlags(): void
    {
        if ($this->statusIdTo !== null) {
            $this->isWonTransition = $this->statusIdTo === \AmoCRM\Models\LeadModel::WON_STATUS_ID;
            $this->isLostTransition = $this->statusIdTo === \AmoCRM\Models\LeadModel::LOST_STATUS_ID;
        }
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $data): self
    {
        $model = new self();

        $model->setId($data['id'] ?? null);
        $model->setLeadId($data['lead_id'] ?? null);
        $model->setStatusIdFrom($data['status_id_from'] ?? null);
        $model->setStatusIdTo($data['status_id_to'] ?? null);
        $model->setPipelineId($data['pipeline_id'] ?? null);
        $model->setChangedBy($data['changed_by'] ?? null);
        $model->setChangedAt($data['changed_at'] ?? null);
        $model->setDurationSeconds($data['duration_seconds'] ?? null);
        $model->setStatusFromName($data['status_from_name'] ?? null);
        $model->setStatusToName($data['status_to_name'] ?? null);
        $model->setIsWonTransition($data['is_won_transition'] ?? false);
        $model->setIsLostTransition($data['is_lost_transition'] ?? false);
        $model->setCreatedAt($data['created_at'] ?? null);
        $model->setUpdatedAt($data['updated_at'] ?? null);
        $model->setAccountId($data['account_id'] ?? null);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'lead_id' => $this->leadId,
            'status_id_from' => $this->statusIdFrom,
            'status_id_to' => $this->statusIdTo,
            'pipeline_id' => $this->pipelineId,
            'changed_by' => $this->changedBy,
            'changed_at' => $this->changedAt,
            'duration_seconds' => $this->durationSeconds,
            'status_from_name' => $this->statusFromName,
            'status_to_name' => $this->statusToName,
            'is_won_transition' => $this->isWonTransition,
            'is_lost_transition' => $this->isLostTransition,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'account_id' => $this->accountId,
        ];
    }

    /**
     * Создать LeadStatusHistoryModel из API EventModel
     *
     * @param EventModel $eventModel
     * @param int|null $previousEventTimestamp Время предыдущего события для расчёта duration
     * @return self
     */
    public static function fromApiModel(EventModel $eventModel, ?int $previousEventTimestamp = null): self
    {
        $model = new self();

        $valueBefore = $eventModel->getValueBefore() ?? [];
        $valueAfter = $eventModel->getValueAfter() ?? [];

        $model->setLeadId($eventModel->getEntityId())
            ->setChangedBy($eventModel->getCreatedBy())
            ->setChangedAt($eventModel->getCreatedAt())
            ->setStatusIdFrom($valueBefore['status_id'] ?? null)
            ->setStatusIdTo($valueAfter['status_id'] ?? null);

        // Рассчитываем duration если есть предыдущее событие
        if ($previousEventTimestamp !== null && $eventModel->getCreatedAt() !== null) {
            $model->setDurationSeconds($eventModel->getCreatedAt() - $previousEventTimestamp);
        }

        return $model;
    }

    /**
     * Создать модель из данных вебхука
     *
     * @param array $webhookData Данные из вебхука
     * @return self
     */
    public static function fromWebhook(array $webhookData): self
    {
        $model = new self();

        $model->setLeadId($webhookData['leads']['id'] ?? null)
            ->setChangedBy($webhookData['account_id'] ?? null)
            ->setChangedAt(time())
            ->setStatusIdFrom($webhookData['leads']['status']['from'] ?? null)
            ->setStatusIdTo($webhookData['leads']['status']['to'] ?? null);

        return $model;
    }
}