<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;

class ContactCreatedNote extends OnlyTextParamNote
{
    protected $modelClass = ContactCreatedNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_CONTACT_CREATED;
    }
}
