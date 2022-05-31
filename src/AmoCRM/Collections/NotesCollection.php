<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\NoteModel;

/**
 * Class NotesCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|NoteModel current()
 * @method null|NoteModel last()
 * @method null|NoteModel first()
 * @method null|NoteModel offsetGet($offset)
 * @method void offsetSet($offset, NoteModel $value)
 * @method NotesCollection prepend(NoteModel $value)
 * @method NotesCollection add(NoteModel $value)
 * @method null|NoteModel getBy($key, $value)
 */
class NotesCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = NoteModel::class;

    /**
     * @param array $array
     *
     * @return self
     */
    public static function fromArray(array $array): BaseApiCollection
    {
        return self::make(
            array_map(
                function (array $item) {
                    return NoteFactory::createForType($item['note_type'], $item);
                },
                $array
            )
        );
    }
}
