<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;

class CompanyCreatedNote extends OnlyTextParamNote
{
    protected $modelClass = CompanyCreatedNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_COMPANY_CREATED;
    }
}
