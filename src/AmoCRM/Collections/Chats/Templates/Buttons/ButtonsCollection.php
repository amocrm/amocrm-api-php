<?php

declare(strict_types=1);

namespace AmoCRM\Collections\Chats\Templates\Buttons;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\Chats\Templates\Buttons\AbstractButtonModel;

/**
 * Class ButtonsCollection
 *
 * @package AmoCRM\Collections\Chats\Templates\Buttons
 *
 * @method null|AbstractButtonModel current()
 * @method null|AbstractButtonModel last()
 * @method null|AbstractButtonModel first()
 * @method null|AbstractButtonModel offsetGet($offset)
 * @method void offsetSet($offset, AbstractButtonModel $value)
 * @method ButtonsCollection prepend(AbstractButtonModel $value)
 * @method ButtonsCollection add(AbstractButtonModel $value)
 * @method null|AbstractButtonModel getBy($key, $value)
 */
class ButtonsCollection extends BaseApiCollection
{
    public const ITEM_CLASS = AbstractButtonModel::class;

    /**
     * @param array $array
     *
     * @return ButtonsCollection
     */
    public static function fromArray(array $array): BaseApiCollection
    {
        $items = array_map(
            static function (array $item) {
                try {
                    return AbstractButtonModel::fromArray($item);
                } catch (InvalidArgumentException $e) {
                    return null;
                }
            },
            $array
        );

        return self::make($items);
    }
}
