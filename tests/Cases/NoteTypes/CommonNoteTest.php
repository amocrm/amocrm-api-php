<?php
declare(strict_types=1);

namespace Tests\Cases\NoteTypes;

use AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Models\NoteType\CommonNote;
use PHPUnit\Framework\TestCase;

final class CommonNoteTest extends TestCase
{
    public function testCanBeCreatedFromValidNote(): void
    {
        $id = 1254829;
        $entityId = 8694432;
        $userId = 501;
        $timestamp = 1587731353;
        $accountId = 123;

        $text = 'Common note';

        $note = [
            'id' => $id,
            'entity_id' => $entityId,
            'created_by' => $userId,
            'updated_by' => $userId,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
            'responsible_user_id' => $userId,
            'group_id' => 0,
            'note_type' => 'common',
            'params' => [
                'text' => $text,
            ],
            'account_id' => $accountId,
        ];

        $commonNote = (new CommonNote())->fromArray($note);
        $this->assertInstanceOf(
            CommonNote::class,
            $commonNote
        );

        $this->assertSame($id, $commonNote->getId());
        $this->assertSame($entityId, $commonNote->getEntityId());
        $this->assertSame($userId, $commonNote->getCreatedBy());
        $this->assertSame($userId, $commonNote->getUpdatedBy());
        $this->assertSame($userId, $commonNote->getResponsibleUserId());
        $this->assertSame(0, $commonNote->getGroupId());
        $this->assertSame($timestamp, $commonNote->getCreatedAt());
        $this->assertSame($timestamp, $commonNote->getUpdatedAt());
        $this->assertSame(NoteFactory::NOTE_TYPE_CODE_COMMON, $commonNote->getNoteType());
        $this->assertSame($text, $commonNote->getText());
    }
}
