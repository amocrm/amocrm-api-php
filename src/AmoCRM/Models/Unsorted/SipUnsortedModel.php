<?php

namespace AmoCRM\Models\Unsorted;

use AmoCRM\Models\Unsorted\Interfaces\UnsortedMetadataInterface;
use AmoCRM\Exceptions\BadTypeException;

class SipUnsortedModel extends BaseUnsortedModel
{
    /**
     * @var string
     */
    protected $category = BaseUnsortedModel::CATEGORY_CODE_SIP;

    /**
     * @param string $category
     * @return BaseUnsortedModel
     */
    public function setCategory(string $category): BaseUnsortedModel
    {
        $this->category = BaseUnsortedModel::CATEGORY_CODE_SIP;

        return $this;
    }

    /**
     * @return UnsortedMetadataInterface|null
     */
    public function getMetadata(): ?UnsortedMetadataInterface
    {
        return $this->metadata;
    }

    /**
     * @param UnsortedMetadataInterface $metadata
     *
     * @return BaseUnsortedModel
     * @throws BadTypeException
     */
    public function setMetadata(UnsortedMetadataInterface $metadata): BaseUnsortedModel
    {
        if (!($metadata instanceof SipMetadata)) {
            throw new BadTypeException('metadata should be instance of SipMetadata');
        }
        $this->metadata = $metadata;

        return $this;
    }
}
