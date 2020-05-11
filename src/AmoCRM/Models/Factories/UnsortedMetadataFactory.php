<?php

namespace AmoCRM\Models\Factories;

use AmoCRM\Models\Unsorted\Interfaces\UnsortedMetadataInterface;
use AmoCRM\Models\Unsorted\BaseUnsortedModel;
use AmoCRM\Models\Unsorted\ChatsMetadata;
use AmoCRM\Models\Unsorted\FormsMetadata;
use AmoCRM\Models\Unsorted\MailMetadata;
use AmoCRM\Models\Unsorted\SipMetadata;

class UnsortedMetadataFactory
{
    /**
     * @param string $category
     * @param array $metadata
     * @return UnsortedMetadataInterface
     */
    public static function createForCategory(string $category, array $metadata): UnsortedMetadataInterface
    {
        switch ($category) {
            case BaseUnsortedModel::CATEGORY_CODE_CHATS:
                return (new ChatsMetadata())->fromArray($metadata);
                break;
            case BaseUnsortedModel::CATEGORY_CODE_FORMS:
                return (new FormsMetadata())->fromArray($metadata);
                break;
            case BaseUnsortedModel::CATEGORY_CODE_SIP:
                return (new SipMetadata())->fromArray($metadata);
                break;
            case BaseUnsortedModel::CATEGORY_CODE_MAIL:
                return (new MailMetadata())->fromArray($metadata);
                break;
        }
    }
}
