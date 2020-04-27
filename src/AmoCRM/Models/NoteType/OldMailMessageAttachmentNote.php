<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;

/**
 * Class OldMailMessageAttachmentNote
 * @package AmoCRM\Models\NoteType
 * @deprecated
 */
class OldMailMessageAttachmentNote extends NoParamNote
{
    protected $modelClass = OldMailMessageAttachmentNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_MAIL_MESSAGE_ATTACHMENT;
    }
}
