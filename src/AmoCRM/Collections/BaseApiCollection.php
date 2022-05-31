<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\BaseApiModel;
use ArrayAccess;
use ArrayIterator;
use AmoCRM\Support\Str;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

use function array_column;
use function array_combine;
use function array_keys;
use function count;

/**
 * Class BaseApiCollection
 *
 * @package AmoCRM\Collections
 */
abstract class BaseApiCollection implements ArrayAccess, JsonSerializable, IteratorAggregate
{
    /**
     * Класс модели
     * @var string
     */
    const ITEM_CLASS = '';

    /**
     * @param mixed $item
     * @return BaseApiModel
     */
    protected function checkItem($item): BaseApiModel
    {
        $class = static::ITEM_CLASS;
        if (!is_object($item) || !($item instanceof $class)) {
            throw new InvalidArgumentException('Item must be an instance of ' . $class);
        }

        return $item;
    }

    /**
     * @param array $array
     * @return self
     */
    public static function fromArray(array $array): self
    {
        $itemClass = static::ITEM_CLASS;

        return self::make(
            array_map(
                function (array $item) use ($itemClass) {
                    /** @var BaseApiModel $itemObj */
                    $itemObj = new $itemClass();
                    return $itemObj->fromArray($item);
                },
                $array
            )
        );
    }

    /**
     * @param array $items
     * @return static
     */
    public static function make(array $items): BaseApiCollection
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
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $this->checkItem($value);
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
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
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
     * @return null|array
     */
    public function toApi(): ?array
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
    public function getIterator(): Traversable
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
        $getter = (method_exists(static::ITEM_CLASS, 'get' . $key) ? 'get' . $key : null);

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

    /**
     * Замена объекта в коллекции по параметру объекта
     *
     * @param string $key
     * @param mixed $value
     * @param BaseApiModel $replacement
     *
     * @return void
     */
    public function replaceBy($key, $value, BaseApiModel $replacement): void
    {
        $key = Str::ucfirst(Str::camel($key));
        $getter = (method_exists(static::ITEM_CLASS, 'get' . $key) ? 'get' . $key : null);

        if ($getter) {
            foreach ($this->data as &$object) {
                $fieldValue = $object->$getter();

                if ($fieldValue === $value) {
                    $object = $replacement;
                    break;
                }
            }
            unset($object);
        }
    }

    /**
     * Разделение коллекции на массив состоящий из коллекций определенной длинны
     *
     * @param int $size
     * @return BaseApiCollection[]
     */
    public function chunk(int $size): array
    {
        if ($this->count() < $size) {
            return [$this];
        }
        $result = [new static()];
        foreach ($this->data as $item) {
            if ((end($result)->count()) >= $size) {
                $result[] = new static();
            }
            end($result)->add($item);
        }
        return $result;
    }

    /**
     * Удаление объектов из коллекции по параметру объекта
     *
     * @param string $key
     * @param mixed $value
     *
     * @return int count
     */
    public function removeBy($key, $value): int
    {
        $key = Str::ucfirst(Str::camel($key));
        $getter = (method_exists(static::ITEM_CLASS, 'get' . $key) ? 'get' . $key : null);

        $count = 0;
        if ($getter) {
            foreach ($this->data as $dataKey => $object) {
                $fieldValue = $object->$getter();

                if ($fieldValue === $value) {
                    $this->offsetUnset($dataKey);
                    $count++;
                }
            }
        }
        return $count;
    }

    /**
     * Удаление первого объекта из коллекции по параметру объекта
     *
     * @param string $key
     * @param mixed $value
     *
     * @return bool
     */
    public function removeFirstBy($key, $value): bool
    {
        $key = Str::ucfirst(Str::camel($key));
        $getter = (method_exists(static::ITEM_CLASS, 'get' . $key) ? 'get' . $key : null);

        if ($getter) {
            foreach ($this->data as $dataKey => $object) {
                $fieldValue = $object->$getter();

                if ($fieldValue === $value) {
                    $this->offsetUnset($dataKey);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param string $column
     *
     * @return array
     */
    public function pluck(string $column): array
    {
        $data = $this->toArray();
        $values = array_column($data, $column);
        if (count($values) !== count($data)) {
            throw new InvalidArgumentException("Some elements missing keys \"{$column}\"");
        }

        return array_combine(array_keys($data), $values);
    }
}
