<?php

namespace AmoCRM\Models\CustomFields;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Contracts\Support\Arrayable;

use function is_null;
use function preg_match;

/**
 * Class NestedModel
 *
 * @package AmoCRM\Models\CustomFields
 */
class NestedModel extends BaseApiModel implements Arrayable
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var int|null
     */
    protected $parentId;

    /**
     * @var string|null
     */
    protected $requestId;

    /**
     * @var string|null
     */
    protected $parentRequestId;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var int
     */
    protected $sort;

    /**
     * @param array $nested
     *
     * @return self
     */
    public static function fromArray(array $nested): self
    {
        $model = new self();

        $model
            ->setId($nested['id'])
            ->setValue($nested['value'])
            ->setParentId($nested['parent_id'])
            ->setSort($nested['sort']);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'parent_id' => $this->getParentId(),
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
     * @return NestedModel
     */
    public function setId(int $id): NestedModel
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
     * @return NestedModel
     */
    public function setValue(string $value): NestedModel
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
     * @return NestedModel
     */
    public function setSort(int $sort): NestedModel
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * @param int|null $parentId
     *
     * @return NestedModel
     */
    public function setParentId(?int $parentId): NestedModel
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * @param string $requestId
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setRequestId(string $requestId): self
    {
        $this->validateRequestId($requestId);

        $this->requestId = $requestId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    /**
     * @param string $requestId
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setParentRequestId(string $requestId): self
    {
        $this->validateRequestId($requestId);

        $this->parentRequestId = $requestId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getParentRequestId(): ?string
    {
        return $this->parentRequestId;
    }

    /**
     * @param string $requestId
     *
     * @throws InvalidArgumentException
     */
    protected function validateRequestId(string $requestId): void
    {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $requestId)) {
            throw new InvalidArgumentException('Request id must consists of only letters and numbers');
        }
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = [
            'parent_id' => $this->getParentId(),
            'value' => $this->getValue(),
            'sort' => $this->getSort(),
        ];

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }
        if (!is_null($this->getRequestId())) {
            $result['request_id'] = $this->getRequestId();
        }
        if (!is_null($this->getParentRequestId())) {
            $result['parent_request_id'] = $this->getParentRequestId();
        }

        return $result;
    }
}
