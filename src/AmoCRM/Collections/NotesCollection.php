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
 * @method NoteModel current() : ?BaseApiModel
 * @method NoteModel last() : ?BaseApiModel
 * @method NoteModel first() : ?BaseApiModel
 * @method NoteModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, NoteModel $value) : BaseApiCollection
 * @method self prepend(NoteModel $value) : BaseApiCollection
 * @method self add(NoteModel $value) : BaseApiCollection
 * @method NoteModel getBy($key, $value) : ?BaseApiModel
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
