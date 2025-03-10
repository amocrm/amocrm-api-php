<?php

declare(strict_types=1);

namespace AmoCRM\Filters;

class ChatLinksFilter extends BaseEntityFilter
{
    /** @var int|null */
    private $contactId;

    /** @var string|null */
    private $chatId;

    public function getContactId(): ?int
    {
        return $this->contactId;
    }

    public function setContactId(?int $contactId): self
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

    public function buildFilter(): array
    {
        $filter = [];

        if (!is_null($this->getContactId())) {
            $filter['contact_id'] = $this->getContactId();
        }

        if (!is_null($this->getChatId())) {
            $filter['chat_id'] = $this->getChatId();
        }

        return $filter;
    }
}
