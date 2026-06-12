<?php

namespace AmoCRM\Analytics\Models;

use AmoCRM\Analytics\BaseAnalyticsModel;
use AmoCRM\Models\CallModel;

/**
 * Class CallFactModel
 *
 * Модель для хранения данных о звонках (из вебхуков).
 * Звонки не имеют API метода для получения, только создание,
 * поэтому аналитика звонков строится на основе вебхуков.
 *
 * @package AmoCRM\Analytics\Models
 */
class CallFactModel extends BaseAnalyticsModel
{
    /**
     * Уникальный ID звонка
     * @var string|null
     */
    protected $callUniq;

    /**
     * ID звонка (из API)
     * @var int|null
     */
    protected $callId;

    /**
     * Длительность звонка (секунды)
     * @var int|null
     */
    protected $duration;

    /**
     * Источник звонка
     * @var string|null
     */
    protected $source;

    /**
     * Ссылка на запись звонка
     * @var string|null
     */
    protected $link;

    /**
     * Номер телефона
     * @var string|null
     */
    protected $phone;

    /**
     * Результат звонка
     * @var string|null
     */
    protected $callResult;

    /**
     * Статус звонка
     * @var int|null
     */
    protected $callStatus;

    /**
     * Направление звонка (inbound/outbound)
     * @var string|null
     */
    protected $direction;

    /**
     * ID связанной сущности
     * @var int|null
     */
    protected $entityId;

    /**
     * Тип связанной сущности
     * @var string|null
     */
    protected $entityType;

    /**
     * ID ответственного пользователя
     * @var int|null
     */
    protected $responsibleUserId;

    /**
     * ID сотрудника (для входящих звонков)
     * @var int|null
     */
    protected $agentId;

    /**
     * Длительность ожидания (секунды)
     * @var int|null
     */
    protected $waitDuration;

    /**
     * Длительность разговора (секунды)
     * @var int|null
     */
    protected $talkDuration;

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->callUniq;
    }

    /**
     * @param string|null $id
     * @return self
     */
    public function setId($id): self
    {
        $this->callUniq = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCallUniq(): ?string
    {
        return $this->callUniq;
    }

    /**
     * @param string|null $callUniq
     * @return self
     */
    public function setCallUniq(?string $callUniq): self
    {
        $this->callUniq = $callUniq;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCallId(): ?int
    {
        return $this->callId;
    }

    /**
     * @param int|null $callId
     * @return self
     */
    public function setCallId(?int $callId): self
    {
        $this->callId = $callId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @param int|null $duration
     * @return self
     */
    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * @param string|null $source
     * @return self
     */
    public function setSource(?string $source): self
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string|null $link
     * @return self
     */
    public function setLink(?string $link): self
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return self
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCallResult(): ?string
    {
        return $this->callResult;
    }

    /**
     * @param string|null $callResult
     * @return self
     */
    public function setCallResult(?string $callResult): self
    {
        $this->callResult = $callResult;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCallStatus(): ?int
    {
        return $this->callStatus;
    }

    /**
     * @param int|null $callStatus
     * @return self
     */
    public function setCallStatus(?int $callStatus): self
    {
        $this->callStatus = $callStatus;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    /**
     * @param string|null $direction
     * @return self
     */
    public function setDirection(?string $direction): self
    {
        $this->direction = $direction;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    /**
     * @param int|null $entityId
     * @return self
     */
    public function setEntityId(?int $entityId): self
    {
        $this->entityId = $entityId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEntityType(): ?string
    {
        return $this->entityType;
    }

    /**
     * @param string|null $entityType
     * @return self
     */
    public function setEntityType(?string $entityType): self
    {
        $this->entityType = $entityType;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getResponsibleUserId(): ?int
    {
        return $this->responsibleUserId;
    }

    /**
     * @param int|null $responsibleUserId
     * @return self
     */
    public function setResponsibleUserId(?int $responsibleUserId): self
    {
        $this->responsibleUserId = $responsibleUserId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAgentId(): ?int
    {
        return $this->agentId;
    }

    /**
     * @param int|null $agentId
     * @return self
     */
    public function setAgentId(?int $agentId): self
    {
        $this->agentId = $agentId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getWaitDuration(): ?int
    {
        return $this->waitDuration;
    }

    /**
     * @param int|null $waitDuration
     * @return self
     */
    public function setWaitDuration(?int $waitDuration): self
    {
        $this->waitDuration = $waitDuration;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTalkDuration(): ?int
    {
        return $this->talkDuration;
    }

    /**
     * @param int|null $talkDuration
     * @return self
     */
    public function setTalkDuration(?int $talkDuration): self
    {
        $this->talkDuration = $talkDuration;
        return $this;
    }

    /**
     * Проверить, является ли звонок входящим
     *
     * @return bool
     */
    public function isInbound(): bool
    {
        return $this->direction === CallModel::DIRECTION_INBOUND;
    }

    /**
     * Проверить, является ли звонок исходящим
     *
     * @return bool
     */
    public function isOutbound(): bool
    {
        return $this->direction === CallModel::DIRECTION_OUTBOUND;
    }

    /**
     * Проверить, был ли звонок принят
     *
     * @return bool
     */
    public function isAnswered(): bool
    {
        return in_array($this->callStatus, [
            CallModel::CALL_STATUS_OK,
            CallModel::CALL_STATUS_BUSY,
        ], true);
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $data): self
    {
        $model = new self();

        $model->setCallUniq($data['call_uniq'] ?? $data['uniq'] ?? null);
        $model->setCallId($data['call_id'] ?? $data['id'] ?? null);
        $model->setDuration($data['duration'] ?? null);
        $model->setSource($data['source'] ?? null);
        $model->setLink($data['link'] ?? null);
        $model->setPhone($data['phone'] ?? null);
        $model->setCallResult($data['call_result'] ?? null);
        $model->setCallStatus($data['call_status'] ?? null);
        $model->setDirection($data['direction'] ?? null);
        $model->setEntityId($data['entity_id'] ?? null);
        $model->setEntityType($data['entity_type'] ?? null);
        $model->setResponsibleUserId($data['responsible_user_id'] ?? null);
        $model->setAgentId($data['agent_id'] ?? null);
        $model->setWaitDuration($data['wait_duration'] ?? null);
        $model->setTalkDuration($data['talk_duration'] ?? null);
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
            'call_uniq' => $this->callUniq,
            'call_id' => $this->callId,
            'duration' => $this->duration,
            'source' => $this->source,
            'link' => $this->link,
            'phone' => $this->phone,
            'call_result' => $this->callResult,
            'call_status' => $this->callStatus,
            'direction' => $this->direction,
            'entity_id' => $this->entityId,
            'entity_type' => $this->entityType,
            'responsible_user_id' => $this->responsibleUserId,
            'agent_id' => $this->agentId,
            'wait_duration' => $this->waitDuration,
            'talk_duration' => $this->talkDuration,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'account_id' => $this->accountId,
        ];
    }

    /**
     * Создать CallFactModel из API CallModel
     *
     * @param CallModel $callModel
     * @return self
     */
    public static function fromApiModel(CallModel $callModel): self
    {
        $model = new self();

        $model->setCallUniq($callModel->getUniq())
            ->setCallId($callModel->getId())
            ->setDuration($callModel->getDuration())
            ->setSource($callModel->getSource())
            ->setLink($callModel->getLink())
            ->setPhone($callModel->getPhone())
            ->setCallResult($callModel->getCallResult())
            ->setCallStatus($callModel->getCallStatus())
            ->setDirection($callModel->getDirection())
            ->setResponsibleUserId($callModel->getResponsibleUserId());

        // Извлекаем информацию о связанной сущности
        $entity = $callModel->getEntity();
        if ($entity !== null) {
            $model->setEntityId($entity->getId());
            if (method_exists($entity, 'getType')) {
                $model->setEntityType($entity->getType());
            }
        }

        return $model;
    }

    /**
     * Создать модель из данных вебхука add_call
     *
     * @param array $webhookData Данные из вебхука
     * @return self
     */
    public static function fromWebhook(array $webhookData): self
    {
        $model = new self();

        $model->setCallUniq($webhookData['uniq'] ?? null)
            ->setDuration($webhookData['duration'] ?? null)
            ->setSource($webhookData['source'] ?? null)
            ->setLink($webhookData['link'] ?? null)
            ->setPhone($webhookData['phone'] ?? null)
            ->setCallResult($webhookData['call_result'] ?? null)
            ->setCallStatus($webhookData['call_status'] ?? null)
            ->setDirection($webhookData['direction'] ?? null)
            ->setEntityId($webhookData['entity_id'] ?? null)
            ->setEntityType($webhookData['entity_type'] ?? null)
            ->setResponsibleUserId($webhookData['responsible_user_id'] ?? null)
            ->setAgentId($webhookData['agent_id'] ?? null)
            ->setWaitDuration($webhookData['wait_duration'] ?? null)
            ->setTalkDuration($webhookData['talk_duration'] ?? null);

        return $model;
    }
}