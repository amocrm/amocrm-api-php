<?php

namespace AmoCRM\Collections\Traits;

use AmoCRM\Collections\BaseApiCollection;

trait PagesTrait
{
    /**
     * @var string
     */
    private $nextPageLink;

    /**
     * @var string
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
     * @return string
     */
    public function getNextPageLink(): string
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
     * @return string
     */
    public function getPrevPageLink(): string
    {
        return $this->prevPageLink;
    }
}
