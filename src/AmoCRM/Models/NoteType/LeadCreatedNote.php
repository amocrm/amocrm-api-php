<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;

class LeadCreatedNote extends OnlyTextParamNote
{
    protected $modelClass = LeadCreatedNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_LEAD_CREATED;
    }
}
