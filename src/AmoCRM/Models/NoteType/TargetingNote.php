<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\NoteModel;

abstract class TargetingNote extends NoteModel
{
    public const TARGETING_NOTE_SERVICE_FACEBOOK = 'facebook';
    public const TARGETING_NOTE_SERVICE_VKONTAKTE = 'vkontakte';
    public const TARGETING_NOTE_SERVICE_MAILCHIMP = 'mailchimp';
    public const TARGETING_NOTE_SERVICE_ADWORDS = 'adwords';

    public const AVAILABLE_SERVICES = [
        self::TARGETING_NOTE_SERVICE_ADWORDS,
        self::TARGETING_NOTE_SERVICE_MAILCHIMP,
        self::TARGETING_NOTE_SERVICE_VKONTAKTE,
        self::TARGETING_NOTE_SERVICE_FACEBOOK,
    ];

    /**
     * @var null|array
     */
    protected $contacts;

    /**
     * @var null|string
     */
    protected $campaign;

    /**
     * @var int|string
     */
    protected $service;

    /**
     * @param array $note
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public function fromArray(array $note): NoteModel
    {
        $model = parent::fromArray($note);

        if (isset($note['params']['contacts'])) {
            $model->setContacts($note['params']['contacts']);
        }

        if (isset($note['params']['campaign'])) {
            $model->setCampaign($note['params']['campaign']);
        }

        if (isset($note['params']['service'])) {
            $model->setService($note['params']['service']);
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
            'contacts' => $this->getContacts(),
            'campaign' => $this->getCampaign(),
            'service' => $this->getService(),
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
            'contacts' => $this->getContacts(),
            'campaign' => $this->getCampaign(),
            'service' => $this->getService(),
        ];

        return $result;
    }

    /**
     * @return null|array
     */
    public function getContacts(): ?array
    {
        return $this->contacts;
    }

    /**
     * @param array|null $contacts
     * @return TargetingNote
     */
    public function setContacts(?array $contacts): TargetingNote
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCampaign(): ?string
    {
        return $this->campaign;
    }

    /**
     * @param string|null $campaign
     * @return TargetingNote
     */
    public function setCampaign(?string $campaign): TargetingNote
    {
        $this->campaign = $campaign;

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
     *
     * @return TargetingNote
     * @throws InvalidArgumentException
     */
    public function setService(?string $service): TargetingNote
    {
        if (!in_array($service, self::AVAILABLE_SERVICES, true)) {
            throw new InvalidArgumentException('Invalid value given:' . $service);
        }

        $this->service = $service;

        return $this;
    }
}
