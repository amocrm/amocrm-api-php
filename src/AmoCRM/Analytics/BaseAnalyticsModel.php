<?php

namespace AmoCRM\Analytics;

use AmoCRM\Models\BaseApiModel;

/**
 * Class BaseAnalyticsModel
 *
 * Базовый класс для всех моделей аналитики.
 * Расширяет BaseApiModel базовыми свойствами для аналитических фактов.
 *
 * @package AmoCRM\Analytics
 */
abstract class BaseAnalyticsModel extends BaseApiModel
{
    /**
     * @var int|null Unix timestamp создания записи
     */
    protected $createdAt;

    /**
     * @var int|null Unix timestamp обновления записи
     */
    protected $updatedAt;

    /**
     * @var bool|null Флаг синхронизации с источником
     */
    protected $isSynced;

    /**
     * @var int|null ID аккаунта amoCRM
     */
    protected $accountId;

    /**
     * @return int|null
     */
    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    /**
     * @param int|null $createdAt
     * @return self
     */
    public function setCreatedAt(?int $createdAt): self
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
     * @return self
     */
    public function setUpdatedAt(?int $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsSynced(): ?bool
    {
        return $this->isSynced;
    }

    /**
     * @param bool|null $isSynced
     * @return self
     */
    public function setIsSynced(?bool $isSynced): self
    {
        $this->isSynced = $isSynced;

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
     * @return self
     */
    public function setAccountId(?int $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Проверить, является ли модель новой (не сохранённой)
     *
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->getId() === null;
    }

    /**
     * Проверить, изменилась ли модель с момента последнего обновления
     *
     * @param int $timestamp Unix timestamp для сравнения
     * @return bool
     */
    public function wasUpdatedSince(int $timestamp): bool
    {
        return $this->updatedAt !== null && $this->updatedAt > $timestamp;
    }

    /**
     * Создать модель из API-модели amoCRM
     *
     * @param BaseApiModel $apiModel Исходная модель из API
     * @return static
     */
    abstract public static function fromApiModel(BaseApiModel $apiModel): self;

    /**
     * @inheritDoc
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = parent::toApi($requestId);

        // Добавляем аналитические поля
        if ($this->createdAt !== null) {
            $result['created_at'] = $this->createdAt;
        }
        if ($this->updatedAt !== null) {
            $result['updated_at'] = $this->updatedAt;
        }
        if ($this->isSynced !== null) {
            $result['is_synced'] = $this->isSynced;
        }
        if ($this->accountId !== null) {
            $result['account_id'] = $this->accountId;
        }

        return $result;
    }
}