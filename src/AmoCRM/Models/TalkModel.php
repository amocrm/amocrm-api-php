<?php

declare(strict_types=1);

namespace AmoCRM\Models;

use AmoCRM\Exceptions\NotAvailableForActionException;

class TalkModel extends BaseApiModel
{
    /** @var int */
    protected $talkId;
    /** @var int */
    protected $createdAt;
    /** @var int */
    protected $updatedAt;
    /** @var int */
    protected $rate;
    /** @var int */
    protected $contactId;
    /** @var string|null */
    protected $chatId;
    /** @var int|null */
    protected $entityId;
    /** @var string|null */
    protected $entityType;
    /** @var bool */
    protected $isInWork;
    /** @var bool */
    protected $isRead;
    /** @var string */
    protected $origin;
    /** @var int|null */
    protected $missedAt;
    /** @var int */
    protected $accountId;

    /**
     * @param array $talk
     *
     * @return self
     */
    public static function fromArray(array $talk): self
    {
        return (new static())
            ->setTalkId((int)$talk['talk_id'])
            ->setCreatedAt((int)$talk['created_at'])
            ->setUpdatedAt((int)$talk['updated_at'])
            ->setRate((int)$talk['rate'])
            ->setContactId((int)$talk['contact_id'])
            ->setChatId(empty($talk['chat_id']) ? null : (string)$talk['chat_id'])
            ->setEntityId(empty($talk['entity_id']) ?: (int)$talk['entity_id'])
            ->setEntityType(empty($talk['entity_type']) ?: (string)$talk['entity_type'])
            ->setIsInWork(!empty($talk['is_in_work']))
            ->setIsRead(!empty($talk['is_read']))
            ->setOrigin((string)($talk['origin'] ?? ''))
            ->setMissedAt(empty($talk['missed_at']) ? null : (int)$talk['missed_at'])
            ->setAccountId((int)$talk['account_id']);
    }

    public function getTalkId(): int
    {
        return $this->talkId;
    }

    public function setTalkId(int $talkId): self
    {
        $this->talkId = $talkId;

        return $this;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(int $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(int $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getRate(): int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getContactId(): int
    {
        return $this->contactId;
    }

    public function setContactId(int $contactId): self
    {
        $this->contactId = $contactId;

        return $this;
    }

    public function getChatId(): ?string
    {
        return $this->chatId;
    }

    public function setChatId(?string $chatId): self
    {
        $this->chatId = $chatId;

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(?int $entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function getEntityType(): ?string
    {
        return $this->entityType;
    }

    public function setEntityType(?string $entityType): self
    {
        $this->entityType = $entityType;

        return $this;
    }

    public function isInWork(): bool
    {
        return $this->isInWork;
    }

    public function setIsInWork(bool $isInWork): self
    {
        $this->isInWork = $isInWork;

        return $this;
    }

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function setOrigin(string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function getMissedAt(): ?int
    {
        return $this->missedAt;
    }

    public function setMissedAt(?int $missedAt): self
    {
        $this->missedAt = $missedAt;

        return $this;
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function setAccountId(int $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'talk_id'     => $this->getTalkId(),
            'created_at'  => $this->getCreatedAt(),
            'updated_at'  => $this->getUpdatedAt(),
            'rate'        => $this->getRate(),
            'contact_id'  => $this->getContactId(),
            'chat_id'     => $this->getChatId(),
            'entity_id'   => $this->getEntityId(),
            'entity_type' => $this->getEntityType(),
            'is_in_work'  => $this->isInWork(),
            'is_read'     => $this->isRead(),
            'origin'      => $this->getOrigin(),
            'missed_at'   => $this->getMissedAt(),
            'account_id'  => $this->getAccountId(),
        ];
    }

    /**
     * @param string|null $requestId
     *
     * @return array
     * @throws NotAvailableForActionException
     */
    public function toApi(?string $requestId = "0"): array
    {
        throw new NotAvailableForActionException();
    }
}
