<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;

class DpTagsAddedNote extends DpTagsNote
{
    protected $modelClass = DpTagsAddedNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_DP_TAGS_ADDED;
    }
}
