<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;

class LeadAutoCreatedNote extends NoParamNote
{
    protected $modelClass = LeadAutoCreatedNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_LEAD_AUTO_CREATED;
    }
}
