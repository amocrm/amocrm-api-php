<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;

class SmsInNote extends TargetingNote
{
    protected $modelClass = SmsInNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_SMS_IN;
    }
}
