<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\NoteModel;

abstract class NoParamNote extends NoteModel
{
    /**
     * @param int|null $requestId
     * @return array
     * @throws NotAvailableForActionException
     */
    public function toApi(int $requestId = null): array
    {
        throw new NotAvailableForActionException();
    }
}
