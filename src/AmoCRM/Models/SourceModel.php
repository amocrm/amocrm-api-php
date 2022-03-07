<?php

namespace AmoCRM\Models;

use AmoCRM\Collections\Sources\SourceServicesCollection;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Traits\RequestIdTrait;
use AmoCRM\Contracts\Support\Arrayable;

class SourceModel extends BaseApiModel implements Arrayable, HasIdInterface
{
    use RequestIdTrait;

    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;


    /**
     * @var bool|null
     */
    protected $default = false;

    /**
     * @var SourceServicesCollection|null
     */
    protected $services = null;

    /**
     * @var string
     */
    protected $externalId;

    /**
     * @var int|null
     */
    protected $pipelineId;

    /**
     * @param  array  $source
     *
     * @return self
     */
    public static function fromArray(array $source): self
    {
        $model = new self();

        $model->setId(isset($source['id']) ? (int)$source['id'] : null);
        $model->setName($source['name']);

        $model->setDefault(isset($source['default']) ? (bool)$source['default'] : null);
        $model->setServices(
            isset($source['services'])
                ? SourceServicesCollection::fromArray((array)$source['services'])
                : null
        );

        $model->setExternalId($source['external_id']);
        $model->setPipelineId(isset($source['pipeline_id']) ? (int)$source['pipeline_id'] : null);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id'          => $this->getId(),
            'name'        => $this->getName(),
            'default'     => $this->isDefault(),
            'external_id' => $this->getExternalId(),
            'pipeline_id' => $this->getPipelineId(),
            'services'    => $this->getServices() ? $this->getServices()->toArray() : null,
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
     * @param  int|null  $id
     *
     * @return SourceModel
     */
    public function setId(?int $id): self
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
     * @param  string  $name
     *
     * @return SourceModel
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return (bool)$this->default;
    }

    /**
     * @return bool|null
     */
    public function getDefault(): ?bool
    {
        return $this->default;
    }

    /**
     * @param  bool|null  $default
     */
    public function setDefault(?bool $default): void
    {
        $this->default = $default;
    }

    /**
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * @param  string  $externalId
     *
     * @return SourceModel
     */
    public function setExternalId(string $externalId): self
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPipelineId(): ?int
    {
        return $this->pipelineId;
    }

    /**
     * @param  int|null  $pipelineId
     *
     * @return SourceModel
     */
    public function setPipelineId(?int $pipelineId): self
    {
        $this->pipelineId = $pipelineId;
        return $this;
    }

    /**
     * @return \AmoCRM\Collections\Sources\SourceServicesCollection|null
     */
    public function getServices(): ?SourceServicesCollection
    {
        return $this->services;
    }

    /**
     * @param  \AmoCRM\Collections\Sources\SourceServicesCollection|null  $services
     */
    public function setServices(?SourceServicesCollection $services): void
    {
        $this->services = $services;
    }


    /**
     * @param  string|null  $requestId
     *
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

        if (!is_null($this->getExternalId())) {
            $result['external_id'] = $this->getExternalId();
        }

        if (!is_null($this->getPipelineId())) {
            $result['pipeline_id'] = $this->getPipelineId();
        }

        if (!is_null($this->getDefault())) {
            $result['default'] = $this->isDefault();
        }

        if (!is_null($this->getServices())) {
            $result['services'] = $this->getServices()->toApi();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }
}
