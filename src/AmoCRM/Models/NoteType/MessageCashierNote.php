<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\NoteModel;

class MessageCashierNote extends NoteModel
{
    protected $modelClass = MessageCashierNote::class;

    public const STATUS_CREATED = 'created';
    public const STATUS_SHOWN = 'shown';
    public const STATUS_CANCELED = 'canceled';

    public const AVAILABLE_STATUSES = [
        self::STATUS_CANCELED,
        self::STATUS_CREATED,
        self::STATUS_SHOWN,
    ];

    /**
     * @var null|string
     */
    protected $status;

    /**
     * @var null|string
     */
    protected $text;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_MESSAGE_CASHIER;
    }

    /**
     * @param array $note
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public function fromArray(array $note): NoteModel
    {
        $model = parent::fromArray($note);

        if (isset($note['params']['status'])) {
            $model->setStatus($note['params']['status']);
        }

        if (isset($note['params']['text'])) {
            $model->setText($note['params']['text']);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['params']['status'] = $this->getStatus();
        $result['params']['text'] = $this->getText();

        return $result;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = parent::toApi($requestId);

        $result['params']['status'] = $this->getStatus();
        $result['params']['text'] = $this->getText();

        return $result;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     *
     * @return MessageCashierNote
     * @throws InvalidArgumentException
     */
    public function setStatus(?string $status): MessageCashierNote
    {
        if (!in_array($status, self::AVAILABLE_STATUSES, true)) {
            throw new InvalidArgumentException('Invalid value given:' . $status);
        }

        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     * @return MessageCashierNote
     */
    public function setText(?string $text): MessageCashierNote
    {
        $this->text = $text;

        return $this;
    }
}
