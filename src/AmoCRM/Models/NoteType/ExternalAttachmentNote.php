<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;

/**
 * Class ExternalAttachmentNote
 * @package AmoCRM\Models\NoteType
 * @deprecated
 */
class ExternalAttachmentNote extends NoParamNote
{
    protected $modelClass = ExternalAttachmentNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_EXTERNAL_ATTACH;
    }
}
