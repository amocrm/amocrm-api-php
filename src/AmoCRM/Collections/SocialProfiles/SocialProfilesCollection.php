<?php

namespace AmoCRM\Collections\SocialProfiles;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\SocialProfiles\SocialProfileModel;

/**
 * Class SocialProfiles
 *
 * @package AmoCRM\Collections\SocialProfiles
 *
 * @method null|SocialProfileModel current()
 * @method null|SocialProfileModel last()
 * @method null|SocialProfileModel first()
 * @method null|SocialProfileModel offsetGet($offset)
 * @method SocialProfilesCollection offsetSet($offset, SocialProfileModel $value)
 * @method SocialProfilesCollection prepend(SocialProfileModel $value)
 * @method SocialProfilesCollection add(SocialProfileModel $value)
 * @method null|SocialProfileModel getBy($key, $value)
 */
class SocialProfilesCollection extends BaseApiCollection
{
    public const ITEM_CLASS = SocialProfileModel::class;
}
