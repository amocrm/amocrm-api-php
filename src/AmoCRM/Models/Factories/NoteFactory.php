<?php

namespace AmoCRM\Models\Factories;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\NoteModel;
use AmoCRM\Models\NoteType\AmoMailMessageNote;
use AmoCRM\Models\NoteType\AttachmentNote;
use AmoCRM\Models\NoteType\BillPaidNote;
use AmoCRM\Models\NoteType\CallInNote;
use AmoCRM\Models\NoteType\CallOutNote;
use AmoCRM\Models\NoteType\ChatNote;
use AmoCRM\Models\NoteType\CommonNote;
use AmoCRM\Models\NoteType\DropboxNote;
use AmoCRM\Models\NoteType\ExtendedServiceMessageNote;
use AmoCRM\Models\NoteType\FollowedLinkNote;
use AmoCRM\Models\NoteType\GeolocationNote;
use AmoCRM\Models\NoteType\InvoicePaidNote;
use AmoCRM\Models\NoteType\MessageCashierNote;
use AmoCRM\Models\NoteType\ServiceMessageNote;
use AmoCRM\Models\NoteType\SiteVisitNote;
use AmoCRM\Models\NoteType\SmsInNote;
use AmoCRM\Models\NoteType\SmsOutNote;
use AmoCRM\Models\NoteType\TargetingInNote;
use AmoCRM\Models\NoteType\TargetingOutNote;
use AmoCRM\Models\NoteType\TransactionNote;
use AmoCRM\Models\NoteType\ZoomMeetingNote;

/**
 * Class NoteFactory
 *
 * @package AmoCRM\Models\Factories
 */
class NoteFactory
{
    public const NOTE_TYPE_CODE_COMMON = 'common';
    public const NOTE_TYPE_CODE_ATTACHMENT = 'attachment';
    public const NOTE_TYPE_CODE_CALL_IN = 'call_in';
    public const NOTE_TYPE_CODE_CALL_OUT = 'call_out';
    public const NOTE_TYPE_CODE_AMOMAIL_MESSAGE = 'amomail_message';
    /** @deprecated */
    public const NOTE_TYPE_CODE_CHAT = 'chat';
    public const NOTE_TYPE_CODE_SITE_VISIT = 'site_visit';
    public const NOTE_TYPE_CODE_TARGETING_IN = 'targeting_in';
    public const NOTE_TYPE_CODE_TARGETING_OUT = 'targeting_out';
    public const NOTE_TYPE_CODE_TRANSACTION = 'transaction';
    public const NOTE_TYPE_CODE_SERVICE_MESSAGE = 'service_message';
    public const NOTE_TYPE_CODE_MESSAGE_CASHIER = 'message_cashier';
    public const NOTE_TYPE_CODE_INVOICE_PAID = 'invoice_paid';
    public const NOTE_TYPE_CODE_GEOLOCATION = 'geolocation';
    public const NOTE_TYPE_CODE_SMS_IN = 'sms_in';
    public const NOTE_TYPE_CODE_SMS_OUT = 'sms_out';
    public const NOTE_TYPE_CODE_EXTENDED_SERVICE_MESSAGE = 'extended_service_message';
    public const NOTE_TYPE_CODE_ZOOM_MEETING = 'zoom_meeting';
    public const NOTE_TYPE_CODE_DROPBOX = 'dropbox_attachment';
    public const NOTE_TYPE_CODE_LINK_FOLLOWED = 'link_followed';
    /**
     * Данное событие отличается от invoice_paid тем, что вызывает событие Счет оплачен в Digital Pipeline
     */
    public const NOTE_TYPE_CODE_BILL_PAID = 'bill_paid';

    /**
     * @param string $type
     * @param array $note
     *
     * @return NoteModel|AmoMailMessageNote|ChatNote|DropboxNote|FollowedLinkNote|GeolocationNote|InvoicePaidNote|MessageCashierNote|TransactionNote|ZoomMeetingNote
     * @throws InvalidArgumentException
     */
    public static function createForType(string $type, array $note)
    {
        switch ($type) {
            case self::NOTE_TYPE_CODE_COMMON:
                return (new CommonNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_ATTACHMENT:
                return (new AttachmentNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_CALL_IN:
                return (new CallInNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_CALL_OUT:
                return (new CallOutNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_AMOMAIL_MESSAGE:
                return (new AmoMailMessageNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_CHAT:
                return (new ChatNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_SITE_VISIT:
                return (new SiteVisitNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_TARGETING_IN:
                return (new TargetingInNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_TARGETING_OUT:
                return (new TargetingOutNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_TRANSACTION:
                return (new TransactionNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_SERVICE_MESSAGE:
                return (new ServiceMessageNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_MESSAGE_CASHIER:
                return (new MessageCashierNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_INVOICE_PAID:
                return (new InvoicePaidNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_GEOLOCATION:
                return (new GeolocationNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_SMS_IN:
                return (new SmsInNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_SMS_OUT:
                return (new SmsOutNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_EXTENDED_SERVICE_MESSAGE:
                return (new ExtendedServiceMessageNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_ZOOM_MEETING:
                return (new ZoomMeetingNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_DROPBOX:
                return (new DropboxNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_LINK_FOLLOWED:
                return (new FollowedLinkNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_BILL_PAID:
                return (new BillPaidNote())->fromArray($note);
                break;
            default:
                return (new NoteModel())->fromArray($note);
                break;
        }
    }
}
