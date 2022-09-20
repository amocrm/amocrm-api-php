<?php

declare(strict_types=1);

namespace AmoCRM\Enum\Chats\Templates\Attachment;

class TypesEnum
{
    public const TYPE_PICTURE = 'picture';
    public const TYPE_FILE = 'file';
    public const TYPE_DOCUMENT = 'document';
    public const TYPE_VIDEO = 'video';

    public static function getAll(): array
    {
        return [
            self::TYPE_PICTURE,
            self::TYPE_FILE,
            self::TYPE_DOCUMENT,
            self::TYPE_VIDEO,
        ];
    }
}
