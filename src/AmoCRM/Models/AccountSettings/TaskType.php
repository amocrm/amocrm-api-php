<?php

namespace AmoCRM\Models\AccountSettings;

use AmoCRM\Models\BaseApiModel;
use Illuminate\Contracts\Support\Arrayable;

class TaskType extends BaseApiModel implements Arrayable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $color;

    /**
     * @var int|null
     */
    protected $iconId;

    /**
     * @var string|null
     */
    protected $code;

    /**
     * @param array $type
     *
     * @return self
     */
    public static function fromArray(array $type): self
    {
        $model = new self();

        $model->setId($type['id']);
        $model->setName($type['name']);
        $model->setColor($type['color']);
        $model->setIconId($type['icon_id']);
        $model->setCode($type['code']);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'color' => $this->getColor(),
            'icon_id' => $this->getIconId(),
            'code' => $this->getCode(),
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     */
    public function setColor(?string $color): void
    {
        $this->color = $color;
    }

    /**
     * @return int|null
     */
    public function getIconId(): ?int
    {
        return $this->iconId;
    }

    /**
     * @param int|null $iconId
     */
    public function setIconId(?int $iconId): void
    {
        $this->iconId = $iconId;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        return [];
    }
}
