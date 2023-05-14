<?php

declare(strict_types=1);

namespace AmoCRM\Collections;

use AmoCRM\Models\Files\FilePreviewModel;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;

/**
 * Class FilePreviewsCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|FilePreviewModel current()
 * @method null|FilePreviewModel last()
 * @method null|FilePreviewModel first()
 * @method null|FilePreviewModel offsetGet($offset)
 * @method void offsetSet($offset, FilePreviewModel $value)
 * @method FilePreviewsCollection prepend(FilePreviewModel $value)
 * @method FilePreviewsCollection add(FilePreviewModel $value)
 * @method null|FilePreviewModel getBy($key, $value)
 */
class FilePreviewsCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = FilePreviewModel::class;
}
