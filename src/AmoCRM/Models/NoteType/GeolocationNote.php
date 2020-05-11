<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Models\NoteModel;

class GeolocationNote extends NoteModel
{
    protected $modelClass = GeolocationNote::class;

    /**
     * @var null|string
     */
    protected $address;

    /**
     * @var null|string
     */
    protected $longitude;

    /**
     * @var null|string
     */
    protected $latitude;

    /**
     * @var null|string
     */
    protected $text;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_GEOLOCATION;
    }

    /**
     * @param array $note
     *
     * @return self
     */
    public function fromArray(array $note): NoteModel
    {
        $model = parent::fromArray($note);

        if (isset($note['params']['address'])) {
            $this->setAddress($note['params']['address']);
        }

        if (isset($note['params']['text'])) {
            $this->setText($note['params']['text']);
        }

        if (isset($note['params']['latitude'])) {
            $this->setLatitude($note['params']['latitude']);
        }

        if (isset($note['params']['longitude'])) {
            $this->setLongitude($note['params']['longitude']);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['params']['address'] = $this->getAddress();
        $result['params']['text'] = $this->getText();
        $result['params']['latitude'] = $this->getLatitude();
        $result['params']['longitude'] = $this->getLongitude();

        return $result;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = null): array
    {
        $result = parent::toApi($requestId);

        $result['params']['address'] = $this->getAddress();
        $result['params']['text'] = $this->getText();
        $result['params']['latitude'] = $this->getLatitude();
        $result['params']['longitude'] = $this->getLongitude();

        return $result;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return MessageCashierNote
     */
    public function setAddress(?string $address): MessageCashierNote
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    /**
     * @param string|null $longitude
     * @return MessageCashierNote
     */
    public function setLongitude(?string $longitude): MessageCashierNote
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    /**
     * @param string|null $latitude
     * @return GeolocationNote
     */
    public function setLatitude(?string $latitude): GeolocationNote
    {
        $this->latitude = $latitude;

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
     * @return GeolocationNote
     */
    public function setText(?string $text): GeolocationNote
    {
        $this->text = $text;
        return $this;
    }
}
