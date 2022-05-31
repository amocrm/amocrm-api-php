<?php

namespace AmoCRM\Collections\Widgets;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Widgets\SettingTemplateModel;

/**
 * Class SettingsTemplatesCollection
 *
 * @package AmoCRM\Collections\Widgets
 *
 * @method null|SettingTemplateModel current()
 * @method null|SettingTemplateModel last()
 * @method null|SettingTemplateModel first()
 * @method null|SettingTemplateModel offsetGet($offset)
 * @method void offsetSet($offset, SettingTemplateModel $value)
 * @method SettingsTemplatesCollection prepend(SettingTemplateModel $value)
 * @method SettingsTemplatesCollection add(SettingTemplateModel $value)
 * @method null|SettingTemplateModel getBy($key, $value)
 */
class SettingsTemplatesCollection extends BaseApiCollection
{
    public const ITEM_CLASS = SettingTemplateModel::class;
}
