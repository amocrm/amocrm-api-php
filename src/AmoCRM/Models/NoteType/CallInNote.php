<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;

class CallInNote extends CallNote
{
    protected $modelClass = CallInNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_CALL_IN;
    }
}
