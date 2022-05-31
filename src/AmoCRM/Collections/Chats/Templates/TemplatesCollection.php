<?php

declare(strict_types=1);

namespace AmoCRM\Collections\Chats\Templates;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;
use AmoCRM\Models\Chats\Templates\TemplateModel;

/**
 * Class TemplatesCollection
 *
 * @package AmoCRM\Collections\Chats\Templates
 *
 * @method null|TemplateModel current()
 * @method null|TemplateModel last()
 * @method null|TemplateModel first()
 * @method null|TemplateModel offsetGet($offset)
 * @method void offsetSet($offset, TemplateModel $value)
 * @method TemplatesCollection prepend(TemplateModel $value)
 * @method TemplatesCollection add(TemplateModel $value)
 * @method null|TemplateModel getBy($key, $value)
 */
class TemplatesCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = TemplateModel::class;
}
