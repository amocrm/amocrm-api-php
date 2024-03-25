<?php

namespace AmoCRM\Models\Sources;

use AmoCRM\Collections\Sources\SourceServicesPagesCollection;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Contracts\Support\Arrayable;

class SourceServiceModel extends BaseApiModel implements Arrayable
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var SourceServicesPagesCollection|null
     */
    protected $pages;

    /**
     * @var SourceServiceParams|null
     */
    protected $params;


    public static function fromArray(array $data): self
    {
        $service = new static();

        $service->setType($data['type'] ?? '');
        $service->setPages(SourceServicesPagesCollection::fromArray((array)($data['pages'] ?? [])));
        if (!empty($data['params'])) {
            $service->setParams(SourceServiceParams::fromArray((array)$data['params']));
        }

        return $service;
    }

    public function toArray(): array
    {
        $result = [
            'type' => $this->getType(),
            'pages' => $this->getPages()->toArray(),
            'params' => [],
        ];
        if ($this->getParams() !== null) {
            $result['params'] = $this->getParams()->toArray();
        }

        return $result;
    }

    public function toApi(string $requestId = null): array
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param  string  $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return \AmoCRM\Collections\Sources\SourceServicesPagesCollection
     */
    public function getPages(): SourceServicesPagesCollection
    {
        if (is_null($this->pages)) {
            $this->pages = new SourceServicesPagesCollection();
        }

        return $this->pages;
    }

    /**
     * @param  \AmoCRM\Collections\Sources\SourceServicesPagesCollection  $pages
     */
    public function setPages(SourceServicesPagesCollection $pages): void
    {
        $this->pages = $pages;
    }

    /**
     * @return SourceServiceParams|null
     */
    public function getParams(): ?SourceServiceParams
    {
        return $this->params;
    }

    /**
     * @param SourceServiceParams|null $params
     */
    public function setParams(?SourceServiceParams $params): void
    {
        $this->params = $params;
    }
}
