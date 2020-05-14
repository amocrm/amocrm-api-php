<?php

namespace AmoCRM\Collections\Widgets;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\Widgets\SettingTemplateModel;

/**
 * Class SettingsTemplatesCollection
 *
 * @package AmoCRM\Collections\Widgets
 *
 * @method SettingTemplateModel current() : ?BaseApiModel
 * @method SettingTemplateModel last() : ?BaseApiModel
 * @method SettingTemplateModel first() : ?BaseApiModel
 * @method SettingTemplateModel offsetGet($offset) : ?BaseApiModel
 * @method self offsetSet($offset, SettingTemplateModel $value) : BaseApiCollection
 * @method self prepend(SettingTemplateModel $value) : BaseApiCollection
 * @method self add(SettingTemplateModel $value) : BaseApiCollection
 * @method SettingTemplateModel getBy($key, $value) : ?BaseApiModel
 */
class SettingsTemplatesCollection extends BaseApiCollection
{
    public const ITEM_CLASS = SettingTemplateModel::class;
}
