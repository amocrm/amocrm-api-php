<?php

namespace AmoCRM\Models\Widgets;

use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Exceptions\InvalidArgumentException;

/**
 * Class WidgetSourceModel
 *
 * @package AmoCRM\Models\Widgets
 */
class WidgetSourceModel extends BaseApiModel
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
        return ['type' => $this->type, 'pages' => $this->pages];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function getPages(): array
    {
        return $this->pages;
    }

    /**
     * @param $pages
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setPages($pages): self
    {
        foreach ($pages as $page) {
            if (!array_key_exists('link', $page) && !array_key_exists('id', $page)) {
                throw new InvalidArgumentException('Neither "link" nor "id" parameter are present:' . json_encode($pages));
            }
        }
        $this->pages = $pages;

        return $this;
    }
}
