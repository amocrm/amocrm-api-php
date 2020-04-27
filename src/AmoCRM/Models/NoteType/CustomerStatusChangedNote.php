<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;

class CustomerStatusChangedNote extends NoParamNote
{
    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_CUSTOMER_STATUS_CHANGED;
    }
}
