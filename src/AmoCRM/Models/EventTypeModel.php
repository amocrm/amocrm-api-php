<?php

declare(strict_types=1);

namespace AmoCRM\Models;

use InvalidArgumentException;

class EventTypeModel extends BaseApiModel
{
    /** @var string */
    protected $key;
    /** @var int */
    protected $type;
    /** @var string */
    protected $lang;

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): EventTypeModel
    {
        $this->key = $key;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): EventTypeModel
    {
        $this->type = $type;

        return $this;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function setLang(string $lang): EventTypeModel
    {
        $this->lang = $lang;

        return $this;
    }

    public static function fromArray(array $eventType): EventTypeModel
    {
        if (empty($eventType['key'])) {
            throw new InvalidArgumentException('EventType key is empty in ' . \json_encode($eventType, JSON_THROW_ON_ERROR));
        }

        $eventTypeModel = new static();

        $eventTypeModel->setKey($eventType['key'])
            ->setType($eventType['type'])
            ->setLang($eventType['lang']);

        return $eventTypeModel;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'key' => $this->getKey(),
            'type' => $this->getType(),
            'lang' => $this->getLang(),
        ];
    }

    public function toApi(?string $requestId = "0"): array
    {
        return [];
    }
}
