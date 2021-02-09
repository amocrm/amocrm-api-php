<?php

namespace AmoCRM\Models\Unsorted;

use AmoCRM\Models\Unsorted\Interfaces\UnsortedMetadataInterface;
use AmoCRM\Models\BaseApiModel;
use Illuminate\Contracts\Support\Arrayable;

class MailMetadata extends BaseApiModel implements Arrayable, UnsortedMetadataInterface
{
    /**
     * @var array|null
     */
    protected $from;

    /**
     * @var string|null
     */
    protected $subject;

    /**
     * @var int|null
     */
    protected $receivedAt;

    /**
     * @var string|null
     */
    protected $threadId;

    /**
     * @var int|null
     */
    protected $messageId;

    /**
     * @var string|null
     */
    protected $contentSummary;

    /**
     * @param array $metadata
     *
     * @return self
     */
    public static function fromArray(array $metadata): self
    {
        $model = new self();

        $model->setFrom($metadata['from']);
        $model->setReceivedAt($metadata['received_at']);
        $model->setSubject($metadata['subject']);
        $model->setThreadId($metadata['thread_id']);
        $model->setMessageId($metadata['message_id']);
        $model->setContentSummary($metadata['content_summary']);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'from' => [
                'email' => $this->getFrom()['email'],
                'name' => $this->getFrom()['name'],
            ],
            'received_at' => $this->getReceivedAt(),
            'subject' => $this->getSubject(),
            'thread_id' => $this->getThreadId(),
            'message_id' => $this->getMessageId(),
            'content_summary' => $this->getContentSummary(),
        ];
    }

    /**
     * @return array|null
     */
    public function getFrom(): ?array
    {
        return $this->from;
    }

    /**
     * @param array|null $from
     *
     * @return MailMetadata
     */
    public function setFrom(?array $from): MailMetadata
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string|null $subject
     *
     * @return MailMetadata
     */
    public function setSubject(?string $subject): MailMetadata
    {
        $this->subject = $subject;

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
     *
     * @return MailMetadata
     */
    public function setReceivedAt(?int $receivedAt): MailMetadata
    {
        $this->receivedAt = $receivedAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getThreadId(): ?string
    {
        return $this->threadId;
    }

    /**
     * @param string|null $threadId
     *
     * @return MailMetadata
     */
    public function setThreadId(?string $threadId): MailMetadata
    {
        $this->threadId = $threadId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMessageId(): ?int
    {
        return $this->messageId;
    }

    /**
     * @param int|null $messageId
     *
     * @return MailMetadata
     */
    public function setMessageId(?int $messageId): MailMetadata
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContentSummary(): ?string
    {
        return $this->contentSummary;
    }

    /**
     * @param string|null $contentSummary
     *
     * @return MailMetadata
     */
    public function setContentSummary(?string $contentSummary): MailMetadata
    {
        $this->contentSummary = $contentSummary;

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

        $result['category'] = BaseUnsortedModel::CATEGORY_CODE_MAIL;

        return $result;
    }
}
