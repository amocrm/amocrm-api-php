<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;

class SmsInNote extends SmsNote
{
    protected $modelClass = SmsInNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_SMS_IN;
    }
}
