<?php

namespace AmoCRM\Collections;

use AmoCRM\Models\Files\FileModel;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;

/**
 * Class FilesCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|FileModel current()
 * @method null|FileModel last()
 * @method null|FileModel first()
 * @method null|FileModel offsetGet($offset)
 * @method void offsetSet($offset, FileModel $value)
 * @method FilesCollection prepend(FileModel $value)
 * @method FilesCollection add(FileModel $value)
 * @method null|FileModel getBy($key, $value)
 */
class FilesCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = FileModel::class;

    /**
     * @return null|array
     */
    public function toDeleteApi(): ?array
    {
        $result = [];
        /** @var FileModel $item */
        foreach ($this->data as $key => $item) {
            $result[$key] = $item->toDeleteApi();
        }

        return $result;
    }
}
