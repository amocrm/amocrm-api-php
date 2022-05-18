<?php

namespace AmoCRM\Models\Sources;

use AmoCRM\Models\BaseApiModel;
use AmoCRM\Contracts\Support\Arrayable;

class SourceServicePageModel extends BaseApiModel implements Arrayable
{

    /**
     * @var string
     */
    protected $id = '';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $link = '';

    public static function fromArray(array $data): self
    {
        $page = new static();

        $page->setId($data['id'] ?? '');
        $page->setName($data['name'] ?? '');
        $page->setLink($data['link'] ?? '');

        return $page;
    }

    public function toArray(): array
    {
        return [
            'id'   => $this->getId(),
            'name' => $this->getName(),
            'link' => $this->getLink(),
        ];
    }

    public function toApi(string $requestId = null): array
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param  string  $id
     */
    public function setId(string $id): void
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
     * @param  string  $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param  string  $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }
}
