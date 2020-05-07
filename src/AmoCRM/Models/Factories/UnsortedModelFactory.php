<?php

namespace AmoCRM\AmoCRM\Models\Factories;

use AmoCRM\Models\Unsorted\BaseUnsortedModel;
use AmoCRM\Models\Unsorted\FormUnsortedModel;
use AmoCRM\Models\Unsorted\SipUnsortedModel;

class UnsortedModelFactory
{
    /**
     * @param string $category
     * @return BaseUnsortedModel
     */
    public static function createForCategory(string $category): BaseUnsortedModel
    {
        switch ($category) {
            case BaseUnsortedModel::CATEGORY_CODE_MAIL:
            case BaseUnsortedModel::CATEGORY_CODE_CHATS:
                return new BaseUnsortedModel();
                break;
            case BaseUnsortedModel::CATEGORY_CODE_FORMS:
                return new FormUnsortedModel();
                break;
            case BaseUnsortedModel::CATEGORY_CODE_SIP:
                return new SipUnsortedModel();
                break;
        }
    }
}
