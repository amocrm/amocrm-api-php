<?php

declare(strict_types=1);

namespace AmoCRM\Collections;

use AmoCRM\Models\FileLinkModel;

/**
 * Class LinksCollection
 *
 * @package AmoCRM\Collections
 *
 * @method null|FileLinkModel current()
 * @method null|FileLinkModel last()
 * @method null|FileLinkModel first()
 * @method null|FileLinkModel offsetGet($offset)
 * @method void offsetSet($offset, FileLinkModel $value)
 * @method FileLinksCollection prepend(FileLinkModel $value)
 * @method FileLinksCollection add(FileLinkModel $value)
 * @method null|FileLinkModel getBy($key, $value)
 */
class FileLinksCollection extends BaseApiCollection
{
    /**
     * Класс модели
     * @var string
     */
    public const ITEM_CLASS = FileLinkModel::class;
}
