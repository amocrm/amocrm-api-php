<?php

namespace AmoCRM\AmoCRM\Models\Interfaces;

use AmoCRM\Collections\CustomFieldsValuesCollection;

interface HasCustomFieldsValuesInterface
{
    public function getCustomFieldsValues(): ?CustomFieldsValuesCollection;

    public function setCustomFieldsValues(?CustomFieldsValuesCollection $values);
}
