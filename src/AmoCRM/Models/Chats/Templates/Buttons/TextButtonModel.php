<?php

declare(strict_types=1);

namespace AmoCRM\Models\Chats\Templates\Buttons;

use AmoCRM\Enum\Chats\Templates\Buttons\ButtonsEnums;

/**
 * Class TextButtonModel
 *
 * @package AmoCRM\Models\Chats\Templates\Buttons
 */
class TextButtonModel extends AbstractButtonModel
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'type' => ButtonsEnums::TYPE_TEXT,
            'text' => $this->getText(),
        ];
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return TextButtonModel
     */
    public function setText(string $text): TextButtonModel
    {
        $this->text = $text;

        return $this;
    }
}
