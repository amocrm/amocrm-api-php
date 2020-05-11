<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;

class AmoMailMessageNote extends NoParamNote
{
    protected $modelClass = AmoMailMessageNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_AMOMAIL_MESSAGE;
    }
}
