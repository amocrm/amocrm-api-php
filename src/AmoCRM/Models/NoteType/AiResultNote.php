<?php

declare(strict_types=1);

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Models\NoteModel;

class AiResultNote extends NoteModel
{
    protected $modelClass = AiResultNote::class;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $answerId;

    /**
     * @var int
     */
    protected $talkId;

    /**
     * @var bool
     */
    protected $hasError;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_AI_RESULT;
    }

    /**
     * @param array $note
     *
     * @return self
     */
    public function fromArray(array $note): NoteModel
    {
        $model = parent::fromArray($note);

        if (isset($note['params']['text'])) {
            $model->setText((string)$note['params']['text']);
        }

        if (isset($note['params']['answer_id'])) {
            $model->setAnswerId((string)$note['params']['answer_id']);
        }

        if (isset($note['params']['talk_id'])) {
            $model->setTalkId((int)$note['params']['text']);
        }

        if (isset($note['params']['is_error'])) {
            $model->setHasError((bool)$note['params']['is_error']);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['params']['text'] = $this->getText();
        $result['params']['answer_id'] = $this->getAnswerId();
        $result['params']['talk_id'] = $this->getTalkId();
        $result['params']['is_error'] = $this->hasError();

        return $result;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = parent::toApi($requestId);

        $result['params']['text'] = $this->getText();
        $result['params']['answer_id'] = $this->getAnswerId();
        $result['params']['talk_id'] = $this->getTalkId();
        $result['params']['is_error'] = $this->hasError();

        return $result;
    }

    /**
     * @return string
     */
    public function getAnswerId(): string
    {
        return $this->answerId;
    }

    /**
     * @param string|null $answerId
     *
     * @return AiResultNote
     */
    public function setAnswerId(string $answerId): AiResultNote
    {
        $this->answerId = $answerId;

        return $this;
    }

    /**
     * @return int
     */
    public function getTalkId(): int
    {
        return $this->talkId;
    }

    /**
     * @param int $talkId
     *
     * @return AiResultNote
     */
    public function setTalkId(int $talkId): AiResultNote
    {
        $this->talkId = $talkId;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return AiResultNote
     */
    public function setText(string $text): AiResultNote
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->hasError;
    }

    /**
     * @param bool $hasError
     *
     * @return AiResultNote
     */
    public function setHasError(bool $hasError): AiResultNote
    {
        $this->hasError = $hasError;

        return $this;
    }
}
