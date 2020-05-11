<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;

class CallOutNote extends CallNote
{
    protected $modelClass = CallOutNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_CALL_OUT;
    }
}
