<?php

namespace AmoCRM\Collections\Traits;

use AmoCRM\Collections\BaseApiCollection;

trait PagesTrait
{
    /**
     * @var null|string
     */
    private $nextPageLink;

    /**
     * @var null|string
     */
    private $prevPageLink;

    /**
     * @param string $url
     * @return PagesTrait|BaseApiCollection
     */
    public function setNextPageLink(string $url)
    {
        $this->nextPageLink = $url;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getNextPageLink(): ?string
    {
        return $this->nextPageLink;
    }

    /**
     * @param string $url
     * @return PagesTrait|BaseApiCollection
     */
    public function setPrevPageLink(string $url)
    {
        $this->prevPageLink = $url;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPrevPageLink(): ?string
    {
        return $this->prevPageLink;
    }
}
