<?php

namespace AmoCRM\Models\Widgets;

use AmoCRM\Models\BaseApiModel;
use AmoCRM\Contracts\Support\Arrayable;

class SettingTemplateModel extends BaseApiModel implements Arrayable
{
    /**
     * @var string|null
     */
    protected $key;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var bool|null
     */
    protected $isRequired;

    /**
     * @param array $template
     *
     * @return self
     */
    public static function fromArray(array $template): self
    {
        $model = new self();

        $model
            ->setKey($template['key'])
            ->setName($template['name'])
            ->setType($template['type'])
            ->setIsRequired($template['is_required']);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'key' => $this->getKey(),
            'name' => $this->getName(),
            'type' => $this->getType(),
            'is_required' => $this->getIsRequired(),
        ];
    }

    /**
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param string|null $key
     *
     * @return SettingTemplateModel
     */
    public function setKey(?string $key): SettingTemplateModel
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return SettingTemplateModel
     */
    public function setName(?string $name): SettingTemplateModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     *
     * @return SettingTemplateModel
     */
    public function setType(?string $type): SettingTemplateModel
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsRequired(): ?bool
    {
        return $this->isRequired;
    }

    /**
     * @param bool|null $isRequired
     *
     * @return SettingTemplateModel
     */
    public function setIsRequired(?bool $isRequired): SettingTemplateModel
    {
        $this->isRequired = $isRequired;

        return $this;
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
