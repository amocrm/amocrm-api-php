<?php

declare(strict_types=1);

namespace AmoCRM\Models;

use AmoCRM\Enum\Tags\TagColorsEnum;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\Interfaces\EntityApiInterface;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Traits\RequestIdTrait;
use AmoCRM\Contracts\Support\Arrayable;

use function in_array;

class TagModel extends BaseApiModel implements Arrayable, HasIdInterface, EntityApiInterface
{
    use RequestIdTrait;

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
     * @param array $tag
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $tag): self
    {
        $model = new self();

        $model->setId($tag['id']);
        $model->setName($tag['name']);
        if (isset($tag['color'])) {
            if (!in_array($tag['color'], TagColorsEnum::getAll(), true)) {
                throw new InvalidArgumentException('Invalid tag color');
            }
            $model->setColor($tag['color']);
        }

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
        ];
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return TagModel
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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
     *
     * @return TagModel
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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
     *
     * @return TagModel
     */
    public function setColor(?string $color): TagModel
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = [];

        if (!is_null($this->getName())) {
            $result['name'] = $this->getName();
        }

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        if (!is_null($this->getColor())) {
            $result['color'] = $this->getColor();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }

    /**
     * @return array|null
     */
    public function toEntityApi(): ?array
    {
        $result = [];

        if (!is_null($this->getName())) {
            $result['name'] = $this->getName();
        }

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        return $result ?? null;
    }
}
