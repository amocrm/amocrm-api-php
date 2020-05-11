<?php

namespace AmoCRM\Models\Traits;

use AmoCRM\Models\LinkModel;

/**
 * Trait GetLinkTrait
 *
 * @package AmoCRM\Models\Traits
 */
trait GetLinkTrait
{
    /**
     * @return LinkModel
     */
    public function getLink(): LinkModel
    {
        $linkModel = new LinkModel();
        $linkModel->setToEntityId($this->getId())
            ->setToEntityType($this->getType())
            ->setMetadata($this->getMetadataForLink());

        return $linkModel;
    }

    abstract protected function getMetadataForLink(): ?array;
}
