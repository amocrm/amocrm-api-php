<?php

namespace AmoCRM\Dwh\Models;

class ContactEventDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected int $ContactId;
    protected ?string $EventId = null;
    protected ?string $EventType = null;
    protected ?string $EventValueBefore = null;
    protected ?string $EventValueAfter = null;
    protected ?int $EventCreatedUserId = null;
    protected ?string $EventCreatedAt = null;

    public static function getTableName(): string { return 'amocrm_contacts_events'; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'contact_id' => $this->ContactId,
            'event_id' => $this->EventId,
            'event_type' => $this->EventType,
            'event_value_before' => $this->EventValueBefore,
            'event_value_after' => $this->EventValueAfter,
            'event_created_user_id' => $this->EventCreatedUserId,
            'event_created_at' => $this->EventCreatedAt,
        ];
    }
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getContactId(): int { return $this->ContactId; }
    public function setContactId(int $v): self { $this->ContactId = $v; return $this; }
    public function getEventId(): ?string { return $this->EventId; }
    public function setEventId(?string $v): self { $this->EventId = $v; return $this; }
    public function getEventType(): ?string { return $this->EventType; }
    public function setEventType(?string $v): self { $this->EventType = $v; return $this; }
    public function getEventValueBefore(): ?string { return $this->EventValueBefore; }
    public function setEventValueBefore(?string $v): self { $this->EventValueBefore = $v; return $this; }
    public function getEventValueAfter(): ?string { return $this->EventValueAfter; }
    public function setEventValueAfter(?string $v): self { $this->EventValueAfter = $v; return $this; }
    public function getEventCreatedUserId(): ?int { return $this->EventCreatedUserId; }
    public function setEventCreatedUserId(?int $v): self { $this->EventCreatedUserId = $v; return $this; }
    public function getEventCreatedAt(): ?string { return $this->EventCreatedAt; }
    public function setEventCreatedAt(?string $v): self { $this->EventCreatedAt = $v; return $this; }
}
