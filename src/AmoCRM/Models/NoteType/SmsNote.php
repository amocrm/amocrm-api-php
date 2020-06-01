<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\NoteModel;

abstract class SmsNote extends NoteModel
{
    /**
     * @var null|string
     */
    protected $text;

    /**
     * @var null|string
     */
    protected $phone;

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

        if (isset($note['params']['phone'])) {
            $model->setPhone($note['params']['phone']);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['params'] = [
            'text' => $this->getText(),
            'phone' => $this->getPhone(),
        ];

        return $result;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = parent::toApi($requestId);

        $result['params'] = [
            'text' => $this->getText(),
            'phone' => $this->getPhone(),
        ];

        return $result;
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
     * @return SmsNote
     */
    public function setText(?string $text): SmsNote
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return SmsNote
     */
    public function setPhone(?string $phone): SmsNote
    {
        $this->phone = $phone;

        return $this;
    }
}
