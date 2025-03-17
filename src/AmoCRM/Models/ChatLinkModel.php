<?php

declare(strict_types=1);

namespace AmoCRM\Models;

use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Traits\RequestIdTrait;

class ChatLinkModel extends BaseApiModel
{
    use RequestIdTrait;

    /** @var int */
    protected $contactId;

    /** @var string */
    protected $chatId;

    /**
     * @return int
     */
    public function getContactId(): int
    {
        return $this->contactId;
    }

    public function setContactId(int $contactId): ChatLinkModel
    {
        $this->contactId = $contactId;

        return $this;
    }

    /**
     * @return string
     */
    public function getChatId(): string
    {
        return $this->chatId;
    }

    /**
     * @param string $chatId
     * @return $this
     */
    public function setChatId(string $chatId): ChatLinkModel
    {
        $this->chatId = $chatId;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'contact_id' => $this->getContactId(),
            'chat_id' => $this->getChatId(),
        ];
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = [];

        if (!is_null($this->getContactId())) {
            $result['contact_id'] = $this->getContactId();
        }

        if (!is_null($this->getChatId())) {
            $result['chat_id'] = $this->getChatId();
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }
}
