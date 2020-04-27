<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;

class DpTagsRemovedNote extends DpTagsNote
{
    protected $modelClass = DpTagsRemovedNote::class;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_DP_TAGS_REMOVED;
    }
}
