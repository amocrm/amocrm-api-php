<?php

namespace AmoCRM\Models\Unsorted;

use AmoCRM\Models\Unsorted\Interfaces\UnsortedMetadataInterface;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Contracts\Support\Arrayable;

class ChatsMetadata extends BaseApiModel implements Arrayable, UnsortedMetadataInterface
{
    /**
     * @var string|null
     */
    protected $from;

    /**
     * @var string|null
     */
    protected $to;

    /**
     * @var int|null
     */
    protected $receivedAt;

    /**
     * @var string|null
     */
    protected $service;

    /**
     * @var array|null
     */
    protected $client;

    /**
     * @var array|null
     */
    protected $origin;

    /**
     * @var string|null
     */
    protected $lastMessageText;

    /**
     * @var string|null
     */
    protected $sourceName;

    /**
     * @param array $metadata
     *
     * @return self
     */
    public static function fromArray(array $metadata): self
    {
        $model = new self();

        $model->setFrom($metadata['from']);
        $model->setTo($metadata['to']);
        $model->setReceivedAt($metadata['received_at']);
        $model->setService($metadata['service']);
        $model->setClient($metadata['client']);
        $model->setOrigin($metadata['origin']);
        $model->setLastMessageText($metadata['last_message_text']);
        $model->setSourceName($metadata['source_name']);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'from' => $this->getFrom(),
            'to' => $this->getTo(),
            'received_at' => $this->getReceivedAt(),
            'service' => $this->getService(),
            'client' => [
                'name' => $this->getClient()['name'] ?? null,
                'avatar' => $this->getClient()['avatar'] ?? null,
            ],
            'origin' => [
                'chat_id' => $this->getOrigin()['chat_id'] ?? null,
                'ref' => $this->getOrigin()['ref'] ?? null,
                'visitor_uid' => $this->getOrigin()['visitor_uid'] ?? null,
            ],
            'last_message_text' => $this->getLastMessageText(),
            'source_name' => $this->getSourceName(),
        ];
    }

    /**
     * @return string|null
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }

    /**
     * @param string|null $from
     * @return ChatsMetadata
     */
    public function setFrom(?string $from): ChatsMetadata
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTo(): ?string
    {
        return $this->to;
    }

    /**
     * @param string|null $to
     * @return ChatsMetadata
     */
    public function setTo(?string $to): ChatsMetadata
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getReceivedAt(): ?int
    {
        return $this->receivedAt;
    }

    /**
     * @param int|null $receivedAt
     * @return ChatsMetadata
     */
    public function setReceivedAt(?int $receivedAt): ChatsMetadata
    {
        $this->receivedAt = $receivedAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getService(): ?string
    {
        return $this->service;
    }

    /**
     * @param string|null $service
     * @return ChatsMetadata
     */
    public function setService(?string $service): ChatsMetadata
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getClient(): ?array
    {
        return $this->client;
    }

    /**
     * @param array|null $client
     * @return ChatsMetadata
     */
    public function setClient(?array $client): ChatsMetadata
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getOrigin(): ?array
    {
        return $this->origin;
    }

    /**
     * @param array|null $origin
     * @return ChatsMetadata
     */
    public function setOrigin(?array $origin): ChatsMetadata
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastMessageText(): ?string
    {
        return $this->lastMessageText;
    }

    /**
     * @param string|null $lastMessageText
     * @return ChatsMetadata
     */
    public function setLastMessageText(?string $lastMessageText): ChatsMetadata
    {
        $this->lastMessageText = $lastMessageText;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSourceName(): ?string
    {
        return $this->sourceName;
    }

    /**
     * @param string|null $sourceName
     * @return ChatsMetadata
     */
    public function setSourceName(?string $sourceName): ChatsMetadata
    {
        $this->sourceName = $sourceName;

        return $this;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        return [];
    }


    /**
     * @return array
     */
    public function toComplexApi(): array
    {
        $result = $this->toApi();

        $result['category'] = BaseUnsortedModel::CATEGORY_CODE_CHATS;

        return $result;
    }
}
