<?php

namespace AmoCRM\Models\Factories;

use AmoCRM\Exceptions\BadTypeException;
use AmoCRM\Models\Unsorted\BaseUnsortedModel;
use AmoCRM\Models\Unsorted\FormUnsortedModel;
use AmoCRM\Models\Unsorted\SipUnsortedModel;

/**
 * Class UnsortedModelFactory
 *
 * @package AmoCRM\Models\Factories
 */
class UnsortedModelFactory
{
    /**
     * @param string $category
     *
     * @return BaseUnsortedModel|FormUnsortedModel|SipUnsortedModel
     * @throws BadTypeException
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

        throw new BadTypeException('Given category is not supported - ' . $category);
    }
}
