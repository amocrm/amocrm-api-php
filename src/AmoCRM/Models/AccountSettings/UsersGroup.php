<?php

namespace AmoCRM\Models\AccountSettings;

use AmoCRM\Models\BaseApiModel;
use Illuminate\Contracts\Support\Arrayable;

class UsersGroup extends BaseApiModel implements Arrayable
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
     * @var string
     */
    protected $uuid;

    /**
     * @param array $group
     *
     * @return self
     */
    public static function fromArray(array $group): self
    {
        $model = new self();

        $model->setId($group['id'])
            ->setName($group['name']);

        if (!empty($group['uuid'])) {
            $model->setUuid($group['uuid']);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        if ($this->getUuid()) {
            $result['uuid'] = $this->getUuid();
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @return array
     */
    public function getArrayCopy(): array
    {
        return $this->toArray();
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
     * @return UsersGroup
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
     * @return UsersGroup
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return UsersGroup
     */
    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @param int|null $requestId
     * @return array
     */
    public function toApi(int $requestId = null): array
    {
        return [];
    }
}
