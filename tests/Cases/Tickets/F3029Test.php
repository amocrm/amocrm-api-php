<?php

namespace Cases\Tikets;

use AmoCRM\Collections\Widgets\SettingsTemplatesCollection;
use AmoCRM\Models\Widgets\WidgetModel;
use PHPUnit\Framework\TestCase;

class F3029Test extends TestCase
{
    public function testWidgetModelSettingsTemplate(): void
    {
        $widget = new WidgetModel();
        $widget->settings_template = [];

        $this->assertEquals($widget->settings_template::class, SettingsTemplatesCollection::class);
    }
}
