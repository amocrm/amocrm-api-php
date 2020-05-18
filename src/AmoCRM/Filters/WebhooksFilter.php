<?php

namespace AmoCRM\Filters;

class WebhooksFilter extends BaseEntityFilter
{
    /**
     * @var string|null
     */
    private $destination;

    /**
     * @return string|null
     */
    public function getDestination(): ?string
    {
        return $this->destination;
    }

    /**
     * @param string|null $destination
     *
     * @return WebhooksFilter
     */
    public function setDestination(?string $destination): WebhooksFilter
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * @return array
     */
    public function buildFilter(): array
    {
        $filter = [];

        if (!is_null($this->getDestination())) {
            $filter['filter']['destination'] = $this->getDestination();
        }

        return $filter;
    }
}
