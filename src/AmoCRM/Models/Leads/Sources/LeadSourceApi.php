<?php

declare(strict_types=1);

namespace AmoCRM\Models\Leads\Sources;

class LeadSourceApi
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public static function fromArray(array $source): self
    {
        $m = new self();

        $m
            ->setId((int)$source['id'])
            ->setName((string)$source['name']);

        return $m;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}
