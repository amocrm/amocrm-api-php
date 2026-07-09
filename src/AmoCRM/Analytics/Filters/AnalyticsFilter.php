<?php

namespace AmoCRM\Analytics\Filters;

use AmoCRM\Filters\BaseEntityFilter;

/**
 * Class AnalyticsFilter
 *
 * Универсальный фильтр для аналитических запросов.
 * Комбинирует фильтры по датам, статусам, воронкам и кастомным полям.
 *
 * @package AmoCRM\Analytics\Filters
 */
class AnalyticsFilter extends BaseEntityFilter
{
    /**
     * Фильтр по дате создания
     * @var array|null [from, to]
     */
    protected $createdAt;

    /**
     * Фильтр по дате обновления
     * @var array|null [from, to]
     */
    protected $updatedAt;

    /**
     * Фильтр по дате закрытия
     * @var array|null [from, to]
     */
    protected $closedAt;

    /**
     * Фильтр по ID воронок
     * @var array
     */
    protected $pipelineIds = [];

    /**
     * Фильтр по ID статусов
     * @var array
     */
    protected $statusIds = [];

    /**
     * Фильтр по ID ответственных
     * @var array
     */
    protected $responsibleUserIds = [];

    /**
     * Фильтр по ID источников
     * @var array
     */
    protected $sourceIds = [];

    /**
     * Фильтр по цене
     * @var array [from, to, operator]
     */
    protected $priceRange;

    /**
     * Искать только удалённые
     * @var bool|null
     */
    protected $isDeleted;

    /**
     * Искать только побеждённые
     * @var bool|null
     */
    protected $isWon;

    /**
     * Искать только проигранные
     * @var bool|null
     */
    protected $isLost;

    /**
     * Лимит записей
     * @var int
     */
    protected $limit = 250;

    /**
     * Номер страницы
     * @var int
     */
    protected $page = 1;

    /**
     * Поисковый запрос
     * @var string|null
     */
    protected $query;

    /**
     * ID сущностей для фильтрации
     * @var array
     */
    protected $ids = [];

    /**
     * Установить фильтр по дате создания
     *
     * @param int|null $from Начало периода (timestamp)
     * @param int|null $to Конец периода (timestamp)
     * @return self
     */
    public function setCreatedAt(?int $from, ?int $to = null): self
    {
        $this->createdAt = ['from' => $from, 'to' => $to];
        return $this;
    }

    /**
     * Получить фильтр по дате создания
     *
     * @return array|null
     */
    public function getCreatedAt(): ?array
    {
        return $this->createdAt;
    }

    /**
     * Установить фильтр по дате обновления
     *
     * @param int|null $from Начало периода (timestamp)
     * @param int|null $to Конец периода (timestamp)
     * @return self
     */
    public function setUpdatedAt(?int $from, ?int $to = null): self
    {
        $this->updatedAt = ['from' => $from, 'to' => $to];
        return $this;
    }

    /**
     * Получить фильтр по дате обновления
     *
     * @return array|null
     */
    public function getUpdatedAt(): ?array
    {
        return $this->updatedAt;
    }

    /**
     * Установить фильтр по дате закрытия
     *
     * @param int|null $from Начало периода (timestamp)
     * @param int|null $to Конец периода (timestamp)
     * @return self
     */
    public function setClosedAt(?int $from, ?int $to = null): self
    {
        $this->closedAt = ['from' => $from, 'to' => $to];
        return $this;
    }

    /**
     * Получить фильтр по дате закрытия
     *
     * @return array|null
     */
    public function getClosedAt(): ?array
    {
        return $this->closedAt;
    }

    /**
     * Установить фильтр по воронкам
     *
     * @param array $pipelineIds
     * @return self
     */
    public function setPipelineIds(array $pipelineIds): self
    {
        $this->pipelineIds = $pipelineIds;
        return $this;
    }

    /**
     * @return array
     */
    public function getPipelineIds(): array
    {
        return $this->pipelineIds;
    }

    /**
     * Установить фильтр по статусам
     *
     * @param array $statusIds
     * @return self
     */
    public function setStatusIds(array $statusIds): self
    {
        $this->statusIds = $statusIds;
        return $this;
    }

    /**
     * @return array
     */
    public function getStatusIds(): array
    {
        return $this->statusIds;
    }

    /**
     * Установить фильтр по ответственным
     *
     * @param array $userIds
     * @return self
     */
    public function setResponsibleUserIds(array $userIds): self
    {
        $this->responsibleUserIds = $userIds;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponsibleUserIds(): array
    {
        return $this->responsibleUserIds;
    }

    /**
     * Установить фильтр по источникам
     *
     * @param array $sourceIds
     * @return self
     */
    public function setSourceIds(array $sourceIds): self
    {
        $this->sourceIds = $sourceIds;
        return $this;
    }

    /**
     * @return array
     */
    public function getSourceIds(): array
    {
        return $this->sourceIds;
    }

    /**
     * Установить фильтр по цене
     *
     * @param int|float|null $from Минимальная цена
     * @param int|float|null $to Максимальная цена
     * @return self
     */
    public function setPriceRange($from = null, $to = null): self
    {
        $this->priceRange = ['from' => $from, 'to' => $to];
        return $this;
    }

    /**
     * @return array|null
     */
    public function getPriceRange(): ?array
    {
        return $this->priceRange;
    }

    /**
     * @param bool|null $isDeleted
     * @return self
     */
    public function setIsDeleted(?bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool|null $isWon
     * @return self
     */
    public function setIsWon(?bool $isWon): self
    {
        $this->isWon = $isWon;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsWon(): ?bool
    {
        return $this->isWon;
    }

    /**
     * @param bool|null $isLost
     * @return self
     */
    public function setIsLost(?bool $isLost): self
    {
        $this->isLost = $isLost;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsLost(): ?bool
    {
        return $this->isLost;
    }

    /**
     * @param int $limit
     * @return self
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $page
     * @return self
     */
    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param string|null $query
     * @return self
     */
    public function setQuery(?string $query): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @param array $ids
     * @return self
     */
    public function setIds(array $ids): self
    {
        $this->ids = $ids;
        return $this;
    }

    /**
     * @return array
     */
    public function getIds(): array
    {
        return $this->ids;
    }

    /**
     * @inheritDoc
     */
    public function buildFilter(): array
    {
        $filter = [];

        if (!empty($this->ids)) {
            $filter['id'] = implode(',', $this->ids);
        }

        if (!empty($this->pipelineIds)) {
            $filter['pipeline_id'] = implode(',', $this->pipelineIds);
        }

        if (!empty($this->statusIds)) {
            $filter['status_id'] = implode(',', $this->statusIds);
        }

        if (!empty($this->responsibleUserIds)) {
            $filter['responsible_user_id'] = implode(',', $this->responsibleUserIds);
        }

        if (!empty($this->sourceIds)) {
            $filter['source_id'] = implode(',', $this->sourceIds);
        }

        if (!empty($this->query)) {
            $filter['query'] = $this->query;
        }

        $filter['limit'] = $this->limit;
        $filter['page'] = $this->page;

        // Даты
        if ($this->createdAt !== null) {
            if ($this->createdAt['from'] !== null) {
                $filter['filter[created_at][from]'] = $this->createdAt['from'];
            }
            if ($this->createdAt['to'] !== null) {
                $filter['filter[created_at][to]'] = $this->createdAt['to'];
            }
        }

        if ($this->updatedAt !== null) {
            if ($this->updatedAt['from'] !== null) {
                $filter['filter[updated_at][from]'] = $this->updatedAt['from'];
            }
            if ($this->updatedAt['to'] !== null) {
                $filter['filter[updated_at][to]'] = $this->updatedAt['to'];
            }
        }

        if ($this->closedAt !== null) {
            if ($this->closedAt['from'] !== null) {
                $filter['filter[closed_at][from]'] = $this->closedAt['from'];
            }
            if ($this->closedAt['to'] !== null) {
                $filter['filter[closed_at][to]'] = $this->closedAt['to'];
            }
        }

        // Цена
        if ($this->priceRange !== null) {
            if ($this->priceRange['from'] !== null) {
                $filter['filter[price][from]'] = $this->priceRange['from'];
            }
            if ($this->priceRange['to'] !== null) {
                $filter['filter[price][to]'] = $this->priceRange['to'];
            }
        }

        return $filter;
    }

    /**
     * Установить период для аналитики (последние N дней)
     *
     * @param int $days Количество дней
     * @return self
     */
    public function setLastDays(int $days): self
    {
        $to = time();
        $from = $to - ($days * 86400);

        $this->setCreatedAt($from, $to);
        return $this;
    }

    /**
     * Установить период текущего месяца
     *
     * @return self
     */
    public function setCurrentMonth(): self
    {
        $to = time();
        $from = strtotime('first day of this month 00:00:00');

        $this->setCreatedAt($from, $to);
        return $this;
    }

    /**
     * Установить период предыдущего месяца
     *
     * @return self
     */
    public function setPreviousMonth(): self
    {
        $from = strtotime('first day of previous month 00:00:00');
        $to = strtotime('last day of previous month 23:59:59');

        $this->setCreatedAt($from, $to);
        return $this;
    }

    /**
     * Установить фильтр для "только сделки без сделок с источником"
     *
     * @return self
     */
    public function setOnlyWithSource(): self
    {
        $this->sourceIds = ['not_empty' => true];
        return $this;
    }

    /**
     * Очистить все фильтры
     *
     * @return self
     */
    public function clear(): self
    {
        $this->createdAt = null;
        $this->updatedAt = null;
        $this->closedAt = null;
        $this->pipelineIds = [];
        $this->statusIds = [];
        $this->responsibleUserIds = [];
        $this->sourceIds = [];
        $this->priceRange = null;
        $this->isDeleted = null;
        $this->isWon = null;
        $this->isLost = null;
        $this->query = null;
        $this->ids = [];

        return $this;
    }
}