<?php

namespace AmoCRM\Collections;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\NoteModel;

class NotesCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    protected $itemClass = NoteModel::class;

    /**
     * @param array $array
     *
     * @return self
     */
    public function fromArray(array $array): BaseApiCollection
    {
        $result = $this->make(
            array_map(
                function (array $item) {
                    return NoteFactory::createForType($item['note_type'], $item);
                },
                $array
            )
        );

        return $result;
    }
}
