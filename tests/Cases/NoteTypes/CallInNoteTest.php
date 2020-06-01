<?php
declare(strict_types=1);

namespace Tests\Cases\NoteTypes;

use AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Models\Interfaces\CallInterface;
use AmoCRM\Models\NoteType\CallInNote;
use PHPUnit\Framework\TestCase;

final class CallInNoteTest extends TestCase
{
    public function testCanBeCreatedFromValidNote(): void
    {
        $id = 1254829;
        $entityId = 8694432;
        $userId = 501;
        $timestamp = 1587731353;
        $accountId = 123;

        $uniq = '865a950a-cec3-435f-9ee9-409c6d2be4b7';
        $duration = 130;
        $source = 'test integration';
        $link = 'https://example.test/test.mp3';
        $phone = '+7912312321';
        $callResult = 'Разговор состоялся';
        $callStatus = CallInterface::CALL_STATUS_SUCCESS_CONVERSATION;

        $note = [
            'id' => $id,
            'entity_id' => $entityId,
            'created_by' => $userId,
            'updated_by' => $userId,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
            'responsible_user_id' => $userId,
            'group_id' => 0,
            'note_type' => 'call_in',
            'params' => [
                'uniq' => $uniq,
                'duration' => $duration,
                'source' => $source,
                'link' => $link,
                'phone' => $phone,
                'call_result' => $callResult,
                'call_status' => $callStatus,
            ],
            'account_id' => $accountId,
        ];

        $callNote = (new CallInNote())->fromArray($note);
        $this->assertInstanceOf(
            CallInNote::class,
            $callNote
        );

        $this->assertSame($id, $callNote->getId());
        $this->assertSame($entityId, $callNote->getEntityId());
        $this->assertSame($userId, $callNote->getCreatedBy());
        $this->assertSame($userId, $callNote->getUpdatedBy());
        $this->assertSame($userId, $callNote->getResponsibleUserId());
        $this->assertSame(0, $callNote->getGroupId());
        $this->assertSame($timestamp, $callNote->getCreatedAt());
        $this->assertSame($timestamp, $callNote->getUpdatedAt());
        $this->assertSame(NoteFactory::NOTE_TYPE_CODE_CALL_IN, $callNote->getNoteType());
        $this->assertSame($uniq, $callNote->getUniq());
        $this->assertSame($duration, $callNote->getDuration());
        $this->assertSame($source, $callNote->getSource());
        $this->assertSame($phone, $callNote->getPhone());
        $this->assertSame($callResult, $callNote->getCallResult());
        $this->assertSame($callStatus, $callNote->getCallStatus());
    }
}
