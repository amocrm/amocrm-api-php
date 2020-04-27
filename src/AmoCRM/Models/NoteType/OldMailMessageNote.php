<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;

/**
 * Class OldMailMessageNote
 * @package AmoCRM\Models\NoteType
 * @deprecated
 */
class OldMailMessageNote extends NoParamNote
{
    protected $modelClass = OldMailMessageNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_MAIL_MESSAGE;
    }
}
