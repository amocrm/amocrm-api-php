<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;

/**
 * Class OldCallNote
 * @package AmoCRM\Models\NoteType
 * @deprecated
 */
class OldCallNote extends NoParamNote
{
    protected $modelClass = OldCallNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_CALL;
    }
}
