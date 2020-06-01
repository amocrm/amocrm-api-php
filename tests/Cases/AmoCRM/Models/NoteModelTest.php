<?php
declare(strict_types=1);

namespace Tests\Cases\NoteTypes;

use AmoCRM\Models\NoteType\CallInNote;
use PHPUnit\Framework\TestCase;
use TypeError;

final class NoteModelTest extends TestCase
{
    public function testCannotBeCreatedFromInvalidNote(): void
    {
        $note = [
            'id' => 0,
            'entity_id' => null,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => 1587731353,
            'updated_at' => 1587731353,
            'responsible_user_id' => 1,
            'group_id' => 0,
            'note_type' => 'call_in',
            'params' => [
                'text' => '123123',
            ],
            'account_id' => 123,
        ];

        $this->expectException(TypeError::class);

        (new CallInNote())->fromArray($note);
    }
}
