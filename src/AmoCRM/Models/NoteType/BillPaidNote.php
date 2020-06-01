<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Models\NoteModel;

class BillPaidNote extends NoteModel
{
    protected $modelClass = BillPaidNote::class;

    /**
     * @var null|string
     */
    protected $service;

    /**
     * @var null|string
     */
    protected $iconUrl;

    /**
     * @var null|string
     */
    protected $text;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_BILL_PAID;
    }

    /**
     * @param array $note
     *
     * @return self
     */
    public function fromArray(array $note): NoteModel
    {
        $model = parent::fromArray($note);

        if (isset($note['params']['service'])) {
            $model->setService($note['params']['service']);
        }

        if (isset($note['params']['text'])) {
            $model->setText($note['params']['text']);
        }

        if (isset($note['params']['icon_url'])) {
            $model->setIconUrl($note['params']['icon_url']);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['params']['service'] = $this->getService();
        $result['params']['text'] = $this->getText();
        $result['params']['icon_url'] = $this->getIconUrl();

        return $result;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = parent::toApi($requestId);

        $result['params']['service'] = $this->getService();
        $result['params']['text'] = $this->getText();
        $result['params']['icon_url'] = $this->getIconUrl();

        return $result;
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
     * @return BillPaidNote
     */
    public function setService(?string $service): BillPaidNote
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIconUrl(): ?string
    {
        return $this->iconUrl;
    }

    /**
     * @param string|null $iconUrl
     * @return BillPaidNote
     */
    public function setIconUrl(?string $iconUrl): BillPaidNote
    {
        $this->iconUrl = $iconUrl;

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
     * @return BillPaidNote
     */
    public function setText(?string $text): BillPaidNote
    {
        $this->text = $text;

        return $this;
    }
}
