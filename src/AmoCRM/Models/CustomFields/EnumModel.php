<?php

namespace AmoCRM\Models\CustomFields;

use AmoCRM\Models\BaseApiModel;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class EnumModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class EnumModel extends BaseApiModel implements Arrayable
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var int
     */
    protected $sort;

    /**
     * @param array $enum
     *
     * @return self
     */
    public static function fromArray(array $enum): self
    {
        $model = new self();

        $model
            ->setId($enum['id'])
            ->setValue($enum['value'])
            ->setSort($enum['sort']);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'value' => $this->getValue(),
            'sort' => $this->getSort(),
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
     * @return EnumModel
     */
    public function setId(int $id): EnumModel
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return EnumModel
     */
    public function setValue(string $value): EnumModel
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     *
     * @return EnumModel
     */
    public function setSort(int $sort): EnumModel
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = [
            'value' => $this->getValue(),
            'sort' => $this->getSort(),
        ];

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        return $result;
    }
}
