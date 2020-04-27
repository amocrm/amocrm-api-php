<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;

class CustomerCreatedNote extends NoParamNote
{
    protected $modelClass = CustomerCreatedNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_CUSTOMER_CREATED;
    }
}
