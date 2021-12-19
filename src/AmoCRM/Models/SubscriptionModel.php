<?php

declare(strict_types=1);

namespace AmoCRM\Models;

use AmoCRM\Exceptions\NotAvailableForActionException;

class SubscriptionModel extends BaseApiModel
{
    public const TYPE_USER = 'user';
    public const TYPE_GROUP = 'group';
    /** @var int */
    protected $subscriberId;
    /** @var string */
    protected $type;

    /**
     * @param array $subscription
     *
     * @return self
     */
    public static function fromArray(array $subscription): self
    {
        return (new static())
            ->setSubscriberId((int)$subscription['subscriber_id'])
            ->setType((string)$subscription['type']);
    }

    public function getSubscriberId(): int
    {
        return $this->subscriberId;
    }

    public function setSubscriberId(int $subscriberId): self
    {
        $this->subscriberId = $subscriberId;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isUser(): bool
    {
        return $this->getType() === self::TYPE_USER;
    }

    public function isGroup(): bool
    {
        return $this->getType() === self::TYPE_GROUP;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'subscriber_id' => $this->getSubscriberId(),
            'type'          => $this->getType(),
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
