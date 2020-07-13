<?php

namespace AmoCRM\Models;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Traits\RequestIdTrait;

/**
 * Class TaskModel
 *
 * @package AmoCRM\Models
 */
class TaskModel extends BaseApiModel implements HasIdInterface
{
    use RequestIdTrait;

    public const TASK_TYPE_ID_CALL = 1;
    public const TASK_TYPE_ID_MEETING = 2;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var null|int
     */
    protected $responsibleUserId;

    /**
     * @var null|int
     */
    protected $groupId;

    /**
     * @var null|int
     */
    protected $createdBy;

    /**
     * @var null|int
     */
    protected $updatedBy;

    /**
     * @var null|int
     */
    protected $createdAt;

    /**
     * @var null|int
     */
    protected $updatedAt;

    /**
     * @var null|int
     */
    protected $accountId;

    /**
     * @var null|int
     */
    protected $duration;

    /**
     * @var int|null
     */
    protected $entityId;

    /**
     * @var string|null
     */
    protected $entityType;

    /**
     * @var null|bool
     */
    protected $isCompleted;

    /**
     * @var null|int
     */
    protected $taskTypeId;

    /**
     * @var null|string
     */
    protected $text;

    /**
     * @var null|string
     */
    protected $result;

    /**
     * @var null|int
     */
    protected $completeTill;

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

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
     * @return TaskModel
     */
    public function setResponsibleUserId(?int $responsibleUserId): TaskModel
    {
        $this->responsibleUserId = $responsibleUserId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getGroupId(): ?int
    {
        return $this->groupId;
    }

    /**
     * @param int|null $groupId
     * @return TaskModel
     */
    public function setGroupId(?int $groupId): TaskModel
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    /**
     * @param int|null $createdBy
     * @return TaskModel
     */
    public function setCreatedBy(?int $createdBy): TaskModel
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    /**
     * @param int|null $updatedBy
     * @return TaskModel
     */
    public function setUpdatedBy(?int $updatedBy): TaskModel
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    /**
     * @param int|null $createdAt
     * @return TaskModel
     */
    public function setCreatedAt(?int $createdAt): TaskModel
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getUpdatedAt(): ?int
    {
        return $this->updatedAt;
    }

    /**
     * @param int|null $updatedAt
     * @return TaskModel
     */
    public function setUpdatedAt(?int $updatedAt): TaskModel
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    /**
     * @param int|null $accountId
     * @return TaskModel
     */
    public function setAccountId(?int $accountId): TaskModel
    {
        $this->accountId = $accountId;

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
     * @return TaskModel
     */
    public function setDuration(?int $duration): TaskModel
    {
        $this->duration = $duration;

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
     * @return TaskModel
     */
    public function setEntityId(?int $entityId): TaskModel
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
     * @return TaskModel
     */
    public function setEntityType(?string $entityType): TaskModel
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsCompleted(): ?bool
    {
        return $this->isCompleted;
    }

    /**
     * @param bool|null $isCompleted
     * @return TaskModel
     */
    public function setIsCompleted(?bool $isCompleted): TaskModel
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTaskTypeId(): ?int
    {
        return $this->taskTypeId;
    }

    /**
     * @param int|null $taskTypeId
     * @return TaskModel
     */
    public function setTaskTypeId(?int $taskTypeId): TaskModel
    {
        $this->taskTypeId = $taskTypeId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     * @return TaskModel
     */
    public function setText(?string $text): TaskModel
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResult(): ?string
    {
        return $this->result;
    }

    /**
     * @param string|null $result
     * @return TaskModel
     */
    public function setResult(?string $result): TaskModel
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCompleteTill(): ?int
    {
        return $this->completeTill;
    }

    /**
     * @param int|null $completeTill
     * @return TaskModel
     */
    public function setCompleteTill(?int $completeTill): TaskModel
    {
        $this->completeTill = $completeTill;

        return $this;
    }

    /**
     * @param array $task
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $task): self
    {
        if (empty($task['id'])) {
            throw new InvalidArgumentException('Task id is empty in ' . json_encode($task));
        }

        $taskModel = new self();

        $taskModel->setId((int)$task['id']);

        if (!empty($task['text'])) {
            $taskModel->setText($task['text']);
        }

        if (array_key_exists('responsible_user_id', $task) && !is_null($task['responsible_user_id'])) {
            $taskModel->setResponsibleUserId((int)$task['responsible_user_id']);
        }

        if (array_key_exists('group_id', $task) && !is_null($task['group_id'])) {
            $taskModel->setGroupId((int)$task['group_id']);
        }

        if (array_key_exists('created_by', $task) && !is_null($task['created_by'])) {
            $taskModel->setCreatedBy((int)$task['created_by']);
        }

        if (array_key_exists('updated_by', $task) && !is_null($task['updated_by'])) {
            $taskModel->setUpdatedBy((int)$task['updated_by']);
        }

        if (!empty($task['created_at'])) {
            $taskModel->setCreatedAt($task['created_at']);
        }

        if (!empty($task['updated_at'])) {
            $taskModel->setUpdatedAt($task['updated_at']);
        }

        if (array_key_exists('duration', $task) && !is_null($task['duration'])) {
            $taskModel->setDuration((int)$task['duration']);
        }

        if (!empty($task['entity_type'])) {
            $taskModel->setEntityType($task['entity_type']);
        }

        if (!empty($task['entity_id'])) {
            $taskModel->setEntityId($task['entity_id']);
        }

        if (array_key_exists('is_completed', $task) && !is_null($task['is_completed'])) {
            $taskModel->setIsCompleted((int)$task['is_completed']);
        }

        if (array_key_exists('complete_till', $task) && !is_null($task['complete_till'])) {
            $taskModel->setCompleteTill((int)$task['complete_till']);
        }

        if (!empty($task['task_type_id'])) {
            $taskModel->setTaskTypeId($task['task_type_id']);
        }

        if (!empty($task['result']['text'])) {
            $taskModel->setResult($task['result']['text']);
        }

        if (!empty($task['account_id'])) {
            $taskModel->setAccountId((int)$task['account_id']);
        }

        return $taskModel;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
            'responsible_user_id' => $this->getResponsibleUserId(),
            'group_id' => $this->getGroupId(),
            'created_by' => $this->getCreatedBy(),
            'updated_by' => $this->getUpdatedBy(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'duration' => $this->getDuration(),
            'entity_type' => $this->getEntityType(),
            'entity_id' => $this->getEntityId(),
            'is_completed' => $this->getIsCompleted(),
            'complete_till' => $this->getCompleteTill(),
            'task_type_id' => $this->getTaskTypeId(),
            'result' => $this->getResult(),
            'account_id' => $this->getAccountId(),
        ];
    }

    public function toApi(?string $requestId = "0"): array
    {
        $result = [];

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        if (!is_null($this->getText())) {
            $result['text'] = $this->getText();
        }

        if (!is_null($this->getResponsibleUserId())) {
            $result['responsible_user_id'] = $this->getResponsibleUserId();
        }

        if (!is_null($this->getCreatedBy())) {
            $result['created_by'] = $this->getCreatedBy();
        }

        if (!is_null($this->getUpdatedBy())) {
            $result['updated_by'] = $this->getUpdatedBy();
        }

        if (!is_null($this->getCreatedAt())) {
            $result['created_at'] = $this->getCreatedAt();
        }

        if (!is_null($this->getUpdatedAt())) {
            $result['updated_at'] = $this->getUpdatedAt();
        }

        if (!is_null($this->getDuration())) {
            $result['duration'] = $this->getDuration();
        }

        if (!is_null($this->getEntityType())) {
            $result['entity_type'] = $this->getEntityType();
        }

        if (!is_null($this->getEntityId())) {
            $result['entity_id'] = $this->getEntityId();
        }

        if (!is_null($this->getIsCompleted())) {
            $result['is_completed'] = $this->getIsCompleted();
        }

        if (!is_null($this->getCompleteTill())) {
            $result['complete_till'] = $this->getCompleteTill();
        }

        if (!is_null($this->getTaskTypeId())) {
            $result['task_type_id'] = $this->getTaskTypeId();
        }

        if (!is_null($this->getResult())) {
            $result['result']['text'] = $this->getResult();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }
}
