<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;

class TargetingOutNote extends TargetingNote
{
    protected $modelClass = TargetingOutNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_TARGETING_OUT;
    }
}
