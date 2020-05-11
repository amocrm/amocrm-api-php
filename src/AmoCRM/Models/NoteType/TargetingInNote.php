<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;

class TargetingInNote extends TargetingNote
{
    protected $modelClass = TargetingInNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_TARGETING_IN;
    }
}
