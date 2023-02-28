<?php

declare(strict_types=1);

namespace AmoCRM\Collections\Chats\Templates;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\FilePreviewModel;

/**
 * Class FilesPreviewsCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|FilePreviewModel current()
 * @method null|FilePreviewModel last()
 * @method null|FilePreviewModel first()
 * @method null|FilePreviewModel offsetGet($offset)
 * @method void offsetSet($offset, FilePreviewModel $value)
 * @method FilesPreviewsCollection prepend(FilePreviewModel $value)
 * @method FilesPreviewsCollection add(FilePreviewModel $value)
 * @method null|FilePreviewModel getBy($key, $value)
 */
class FilesPreviewsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = FilePreviewModel::class;
}
