<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\BaseApiModel;
use ArrayAccess;
use ArrayIterator;
use Illuminate\Support\Str;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;

abstract class BaseApiCollection implements ArrayAccess, JsonSerializable, IteratorAggregate
{
    /**
     * Класс модели
     * @var string
     */
    protected $itemClass = '';

    /**
     * @param mixed $item
     * @return BaseApiModel
     */
    protected function checkItem($item): BaseApiModel
    {
        if (!is_object($item) || !($item instanceof $this->itemClass)) {
            throw new InvalidArgumentException('Item must be an instance of ' . ($this->itemClass));
        }

        return $item;
    }

    /**
     * @param array $array
     *
     * @return self
     */
    public function fromArray(array $array): self
    {
        $itemClass = $this->itemClass;
        $result = $this->make(
            array_map(
                function (array $item) use ($itemClass) {
                    /** @var BaseApiModel $itemObj */
                    $itemObj = new $itemClass();
                    return $itemObj->fromArray($item);
                },
                $array
            )
        );

        return $result;
    }

    /**
     * @param array $items
     * @return static
     */
    public function make(array $items): BaseApiCollection
    {
        $collection = new static();
        foreach ($items as $item) {
            $collection->add($item);
        }

        return $collection;
    }

    /**
     * Хранилище элементов коллекции
     * @var array
     */
    protected $data = [];

    /**
     * @param BaseApiModel $value
     * @return $this
     */
    public function add(BaseApiModel $value): self
    {
        $this->data[] = $this->checkItem($value);

        return $this;
    }

    /**
     * @param BaseApiModel $value
     *
     * @return $this
     */
    public function prepend(BaseApiModel $value): self
    {
        array_unshift($this->data, $this->checkItem($value));

        return $this;
    }

    /**
     * @param string|int $offset
     * @param BaseApiModel $value
     * @return $this
     */
    public function offsetSet($offset, $value): self
    {
        $this->data[$offset] = $this->checkItem($value);

        return $this;
    }

    /**
     * @param string|int $offset
     * @return BaseApiModel|null
     */
    public function offsetGet($offset): ?BaseApiModel
    {
        return $this->data[$offset] ?? null;
    }

    /**
     * Get all data
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Получение первого значения
     * @return BaseApiModel|null
     */
    public function first(): ?BaseApiModel
    {
        $first = reset($this->data);
        if (!$first) {
            $first = null;
        }
        return $first;
    }

    /**
     * Получение последнего значения
     * @return BaseApiModel|null
     */
    public function last(): ?BaseApiModel
    {
        $last = end($this->data);
        if (!$last) {
            $last = null;
        }
        return $last;
    }

    /**
     * Очистка коллекции
     * @return $this
     */
    public function clear(): self
    {
        foreach ($this->keys() as $key) {
            $this->offsetUnset($key);
        }

        return $this;
    }

    /**
     * Удаление элемента из коллекции.
     *
     * @param string|int $offset
     *
     * @return $this
     */
    public function offsetUnset($offset): self
    {
        unset($this->data[$offset]);

        return $this;
    }

    /**
     * @param string|int $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->data);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [];
        /** @var BaseApiModel $item */
        foreach ($this->data as $key => $item) {
            $result[$key] = $item->toArray();
        }

        return $result;
    }

    /**
     * @return array
     */
    public function toApi(): array
    {
        $result = [];
        /** @var BaseApiModel $item */
        foreach ($this->data as $key => $item) {
            $result[$key] = $item->toApi($key);
        }

        return $result;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)json_encode($this->toArray());
    }

    /**
     * @return BaseApiModel|null
     */
    public function current(): ?BaseApiModel
    {
        return current($this->data);
    }

    /**
     * @return void
     */
    public function next(): void
    {
        next($this->data);
    }

    /**
     * @return string|int
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return key($this->data) !== null;
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        reset($this->data);
    }

    /**
     * Проверяет коллекцию на пустоту
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    /**
     * Получение итератора
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Поиск объекта в коллекции по параметру объекта
     * @param string $key
     * @param mixed $value
     * @return BaseApiModel|null
     */
    public function getBy($key, $value): ?BaseApiModel
    {
        $result = null;

        $key = Str::ucfirst(Str::camel($key));
        $getter = (method_exists($this->itemClass, 'get' . $key) ? 'get' . $key : null);

        if ($getter) {
            foreach ($this->data as $object) {
                $fieldValue = $object->$getter();

                if ($fieldValue === $value) {
                    $result = $object;
                    break;
                }
            }
        }

        return $result;
    }
}
