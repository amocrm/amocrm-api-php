<?php

namespace AmoCRM\Dwh\Collections;

use AmoCRM\Dwh\Models\BaseDwhModel;
use ArrayAccess;
use ArrayIterator;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

use function count;

abstract class BaseDwhCollection implements ArrayAccess, JsonSerializable, IteratorAggregate
{
    public const ITEM_CLASS = '';

    protected array $data = [];

    protected function checkItem($item): BaseDwhModel
    {
        $class = static::ITEM_CLASS;
        if (!is_object($item) || !($item instanceof $class)) {
            throw new InvalidArgumentException('Item must be an instance of ' . $class);
        }

        return $item;
    }

    public static function fromArray(array $array): self
    {
        $itemClass = static::ITEM_CLASS;

        return self::make(
            array_map(
                static function (array $item) use ($itemClass) {
                    /** @var BaseDwhModel $itemObj */
                    $itemObj = new $itemClass();

                    return $itemObj->fromArray($item);
                },
                $array
            )
        );
    }

    public static function make(array $items): self
    {
        $collection = new static();
        foreach ($items as $item) {
            $collection->add($item);
        }

        return $collection;
    }

    public function add(BaseDwhModel $value): self
    {
        $this->data[] = $this->checkItem($value);

        return $this;
    }

    public function all(): array
    {
        return $this->data;
    }

    public function first(): ?BaseDwhModel
    {
        $first = reset($this->data);
        if (!$first) {
            $first = null;
        }

        return $first;
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this->data as $key => $item) {
            $result[$key] = $item->toArray();
        }

        return $result;
    }

    public function toInsertArray(): array
    {
        $result = [];
        foreach ($this->data as $key => $item) {
            $result[$key] = $item->toInsertArray();
        }

        return $result;
    }

    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $this->checkItem($value);
    }

    public function offsetGet($offset): ?BaseDwhModel
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
