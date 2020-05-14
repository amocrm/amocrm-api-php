<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\NoteModel;

abstract class NoParamNote extends NoteModel
{
    /**
     * @param string|null $requestId
     * @return array
     * @throws NotAvailableForActionException
     */
    public function toApi(?string $requestId = "0"): array
    {
        throw new NotAvailableForActionException();
    }
}
