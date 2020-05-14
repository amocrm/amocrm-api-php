<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Models\NoteModel;

class CommonNote extends OnlyTextParamNote
{
    protected $modelClass = CommonNote::class;

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = NoteModel::toApi($requestId);

        $result['params']['text'] = $this->getText();

        return $result;
    }

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_COMMON;
    }
}
