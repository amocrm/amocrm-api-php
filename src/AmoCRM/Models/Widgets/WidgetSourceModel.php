<?php

namespace AmoCRM\Models\Widgets;

use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\BaseApiModel;
use InvalidArgumentException;

class WidgetSourceModel extends  BaseApiModel
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $pages;

    /**
     * @param array $widgetSource
     * @return static
     */
    public static function fromArray(array $widgetSource): self
    {
        if (empty($widgetSource['type'])) {
            throw new InvalidArgumentException('Widget type is empty in ' . json_encode($widgetSource));
        }
        if(empty($widgetSource['pages'])) {
            throw new InvalidArgumentException('Widget pages is empty in ' . json_encode($widgetSource));
        }

        $model = new self();
        $model->setType($widgetSource['type']);
        $model->setPages($widgetSource['pages']);

        return $model;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return ['type' => $this->type, 'pages' => $this->pages];
    }

    /**
     * @param string|null $requestId
     * @return array
     * @throws NotAvailableForActionException
     */
    public function toApi(string $requestId = null): array
    {
        throw new NotAvailableForActionException('Method not available yet');
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param mixed $pages
     */
    public function setPages($pages): void
    {
        foreach ($pages as $page) {
            if(!array_key_exists('link', $page) and !array_key_exists('id', $page)) {
                throw new InvalidArgumentException('neither the "link" parameter nor the "id" parameter are present in pages:' . json_encode($pages));
            }
        }
        $this->pages = $pages;
    }
}