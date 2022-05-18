<?php

declare(strict_types=1);

namespace AmoCRM\Enum\Tags;

class TagColorsEnum
{
    public const BRIGHT_GRAY = 'EBEBEB';
    public const AMERICAN_SILVER = 'D0D0D0';
    public const PINK_LACE = 'F2DDF7';
    public const TROPICAL_VIOLET = 'D1A4DC';
    public const TULIP = 'FF8F92';
    public const LIGHT_RED = 'FFC8C8';
    public const MEDIUM_SPRING_BUD = 'C7DB8C';
    public const PALE_SPRING_BUD = 'DDEBB5';
    public const VISTA_BLUE = '8699DA';
    public const BABY_BLUE_EYES = 'AABDFF';
    public const MUSTARD = 'FFCE5A';
    public const FLAVESCENT = 'FFE193';
    public const ETON_BLUE = '90CDB0';
    public const AERO_BLUE = 'C6F4DE';
    public const BLUE_BELL = 'A9A5D7';
    public const PALE_LAVENDER = 'D8D5FF';
    public const JORDY_BLUE = '86C0FC';
    public const DARK_RASPBERRY = '832161';
    public const PANSY_PURPLE = '6A0F49';
    public const DEEP_GREEN_CYAN_TURQUOISE = '0C7C59';
    public const YALE_BLUE = '10599D';
    public const JAPANESE_CARMINE = '9D2B32';
    public const LAPIS_LAZULI = '247BA0';

    public static function getAll(): array
    {
        return [
            self::BRIGHT_GRAY,
            self::AMERICAN_SILVER,
            self::PINK_LACE,
            self::TROPICAL_VIOLET,
            self::TULIP,
            self::LIGHT_RED,
            self::MEDIUM_SPRING_BUD,
            self::PALE_SPRING_BUD,
            self::VISTA_BLUE,
            self::BABY_BLUE_EYES,
            self::MUSTARD,
            self::FLAVESCENT,
            self::ETON_BLUE,
            self::AERO_BLUE,
            self::BLUE_BELL,
            self::PALE_LAVENDER,
            self::JORDY_BLUE,
            self::DARK_RASPBERRY,
            self::PANSY_PURPLE,
            self::DEEP_GREEN_CYAN_TURQUOISE,
            self::YALE_BLUE,
            self::JAPANESE_CARMINE,
            self::LAPIS_LAZULI,
        ];
    }
}
