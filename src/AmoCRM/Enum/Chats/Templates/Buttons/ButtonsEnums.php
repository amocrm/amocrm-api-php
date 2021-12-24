<?php

declare(strict_types=1);

namespace AmoCRM\Enum\Chats\Templates\Buttons;

class ButtonsEnums
{
    public const TYPE_TEXT = 'inline';
    public const TYPE_URL = 'url';

    public static function getAll(): array
    {
        return [
            self::TYPE_TEXT,
            self::TYPE_URL,
        ];
    }
}
