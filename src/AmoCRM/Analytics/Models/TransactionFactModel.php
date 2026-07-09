<?php

namespace AmoCRM\Analytics\Models;

use AmoCRM\Analytics\BaseAnalyticsModel;
use AmoCRM\Models\Customers\Transactions\TransactionModel;

/**
 * Class TransactionFactModel
 *
 * Модель аналитического факта для транзакций (покупок).
 * Хранит данные о покупках покупателей.
 *
 * @package AmoCRM\Analytics\Models
 */
class TransactionFactModel extends BaseAnalyticsModel
{
    /**
     * ID транзакции
     * @var int|null
     */
    protected $transactionId;

    /**
     * ID покупателя
     * @var int|null
     */
    protected $customerId;

    /**
     * Сумма покупки
     * @var float|null
     */
    protected $price;

    /**
     * Дата совершения покупки
     * @var int|null
     */
    protected $completedAt;

    /**
     * Комментарий к транзакции
     * @var string|null
     */
    protected $comment;

    /**
     * Внешний ID транзакции
     * @var string|null
     */
    protected $externalId;

    /**
     * Ссылка на чек
     * @var string|null
     */
    protected $receiptLink;

    /**
     * Дата следующей покупки
     * @var int|null
     */
    protected $nextDate;

    /**
     * Сумма следующей покупки
     * @var float|null
     */
    protected $nextPrice;

    /**
     * Флаг удаления
     * @var bool
     */
    protected $isDeleted = false;

    /**
     * Список купленных товаров (сериализованный JSON)
     * @var array|null
     */
    protected $catalogElements;

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->transactionId;
    }

    /**
     * @param int|null $id
     * @return self
     */
    public function setId($id): self
    {
        $this->transactionId = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTransactionId(): ?int
    {
        return $this->transactionId;
    }

    /**
     * @param int|null $transactionId
     * @return self
     */
    public function setTransactionId(?int $transactionId): self
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    /**
     * @param int|null $customerId
     * @return self
     */
    public function setCustomerId(?int $customerId): self
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     * @return self
     */
    public function setPrice($price): self
    {
        $this->price = $price !== null ? (float)$price : null;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCompletedAt(): ?int
    {
        return $this->completedAt;
    }

    /**
     * @param int|null $completedAt
     * @return self
     */
    public function setCompletedAt(?int $completedAt): self
    {
        $this->completedAt = $completedAt;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     * @return self
     */
    public function setComment(?string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    /**
     * @param string|null $externalId
     * @return self
     */
    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReceiptLink(): ?string
    {
        return $this->receiptLink;
    }

    /**
     * @param string|null $receiptLink
     * @return self
     */
    public function setReceiptLink(?string $receiptLink): self
    {
        $this->receiptLink = $receiptLink;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNextDate(): ?int
    {
        return $this->nextDate;
    }

    /**
     * @param int|null $nextDate
     * @return self
     */
    public function setNextDate(?int $nextDate): self
    {
        $this->nextDate = $nextDate;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getNextPrice(): ?float
    {
        return $this->nextPrice;
    }

    /**
     * @param float|null $nextPrice
     * @return self
     */
    public function setNextPrice($nextPrice): self
    {
        $this->nextPrice = $nextPrice !== null ? (float)$nextPrice : null;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     * @return self
     */
    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getCatalogElements(): ?array
    {
        return $this->catalogElements;
    }

    /**
     * @param array|null $catalogElements
     * @return self
     */
    public function setCatalogElements(?array $catalogElements): self
    {
        $this->catalogElements = $catalogElements;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $data): self
    {
        $model = new self();

        $model->setTransactionId($data['transaction_id'] ?? $data['id'] ?? null);
        $model->setCustomerId($data['customer_id'] ?? null);
        $model->setPrice($data['price'] ?? null);
        $model->setCompletedAt($data['completed_at'] ?? null);
        $model->setCreatedAt($data['created_at'] ?? null);
        $model->setUpdatedAt($data['updated_at'] ?? null);
        $model->setComment($data['comment'] ?? null);
        $model->setExternalId($data['external_id'] ?? null);
        $model->setReceiptLink($data['receipt_link'] ?? null);
        $model->setNextDate($data['next_date'] ?? null);
        $model->setNextPrice($data['next_price'] ?? null);
        $model->setIsDeleted($data['is_deleted'] ?? false);
        $model->setCatalogElements($data['catalog_elements'] ?? null);
        $model->setAccountId($data['account_id'] ?? null);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'transaction_id' => $this->transactionId,
            'customer_id' => $this->customerId,
            'price' => $this->price,
            'completed_at' => $this->completedAt,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'comment' => $this->comment,
            'external_id' => $this->externalId,
            'receipt_link' => $this->receiptLink,
            'next_date' => $this->nextDate,
            'next_price' => $this->nextPrice,
            'is_deleted' => $this->isDeleted,
            'catalog_elements' => $this->catalogElements,
            'account_id' => $this->accountId,
        ];
    }

    /**
     * Создать TransactionFactModel из API TransactionModel
     *
     * @param TransactionModel $transactionModel
     * @return self
     */
    public static function fromApiModel(TransactionModel $transactionModel): self
    {
        $model = new self();

        $model->setTransactionId($transactionModel->getId())
            ->setCustomerId($transactionModel->getCustomerId())
            ->setPrice($transactionModel->getPrice())
            ->setCompletedAt($transactionModel->getCompletedAt())
            ->setCreatedAt($transactionModel->getCreatedAt())
            ->setUpdatedAt($transactionModel->getUpdatedAt())
            ->setComment($transactionModel->getComment())
            ->setExternalId($transactionModel->getExternalId())
            ->setReceiptLink($transactionModel->getReceiptLink())
            ->setNextDate($transactionModel->getNextDate())
            ->setNextPrice($transactionModel->getNextPrice())
            ->setIsDeleted($transactionModel->getIsDeleted());

        // Извлекаем информацию о купленных товарах
        $catalogElements = $transactionModel->getCatalogElements();
        if ($catalogElements !== null && !$catalogElements->isEmpty()) {
            $elements = [];
            foreach ($catalogElements as $element) {
                $elements[] = [
                    'catalog_id' => $element->getCatalogId(),
                    'id' => $element->getId(),
                    'quantity' => $element->getQuantity(),
                ];
            }
            $model->setCatalogElements($elements);
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

        $model->setTransactionId($webhookData['id'] ?? null)
            ->setCustomerId($webhookData['customer_id'] ?? null)
            ->setPrice($webhookData['price'] ?? null)
            ->setCompletedAt($webhookData['completed_at'] ?? null)
            ->setComment($webhookData['comment'] ?? null)
            ->setExternalId($webhookData['external_id'] ?? null)
            ->setIsDeleted($webhookData['is_deleted'] ?? false);

        return $model;
    }
}