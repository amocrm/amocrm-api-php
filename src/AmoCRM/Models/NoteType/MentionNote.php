<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\NoteModel;

class MentionNote extends NoteModel
{
    protected $modelClass = MentionNote::class;

    /**
     * @var null|string
     */
    protected $message;

    /**
     * @var null|string
     */
    protected $icon;

    /**
     * @var null|array
     */
    protected $messageTags;

    /**
     * @var null|array
     */
    protected $contact;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_MENTION;
    }

    /**
     * @param array $note
     *
     * @return self
     */
    public function fromArray(array $note): NoteModel
    {
        $model = parent::fromArray($note);

        if (isset($note['params']['message'])) {
            $this->setMessage($note['params']['message']);
        }

        if (isset($note['params']['icon'])) {
            $this->setIcon($note['params']['icon']);
        }

        if (isset($note['params']['message_tags'])) {
            $this->setMessageTags($note['params']['message_tags']);
        }

        if (isset($note['params']['contact'])) {
            $this->setContact($note['params']['contact']);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['params']['message'] = $this->getMessage();
        $result['params']['icon'] = $this->getIcon();
        $result['params']['message_tags'] = $this->getMessageTags();
        $result['params']['contact'] = $this->getContact();

        return $result;
    }

    /**
     * @param int|null $requestId
     * @return array
     * @throws NotAvailableForActionException
     */
    public function toApi(int $requestId = null): array
    {
        throw new NotAvailableForActionException();
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     * @return MentionNote
     */
    public function setMessage(?string $message): MentionNote
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param string|null $icon
     * @return MentionNote
     */
    public function setIcon(?string $icon): MentionNote
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getMessageTags(): ?array
    {
        return $this->messageTags;
    }

    /**
     * @param array|null $messageTags
     * @return MentionNote
     */
    public function setMessageTags(?array $messageTags): MentionNote
    {
        $this->messageTags = $messageTags;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getContact(): ?array
    {
        return $this->contact;
    }

    /**
     * @param array|null $contact
     * @return MentionNote
     */
    public function setContact(?array $contact): MentionNote
    {
        $this->contact = $contact;

        return $this;
    }
}
