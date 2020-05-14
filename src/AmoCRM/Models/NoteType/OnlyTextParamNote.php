<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\NoteModel;

abstract class OnlyTextParamNote extends NoteModel
{
    /**
     * @var null|string
     */
    protected $text;

    /**
     * @param array $note
     *
     * @return self
     */
    public function fromArray(array $note): NoteModel
    {
        $model = parent::fromArray($note);

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

        $result['params']['text'] = $this->getText();

        return $result;
    }

    /**
     * @param string|null $requestId
     * @return array
     * @throws NotAvailableForActionException
     */
    public function toApi(?string $requestId = "0"): array
    {
        throw new NotAvailableForActionException();
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
     * @return NoteModel
     */
    public function setText(string $text): NoteModel
    {
        $this->text = $text;

        return $this;
    }
}
