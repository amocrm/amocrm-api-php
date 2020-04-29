<?php

namespace AmoCRM\AmoCRM\Models\Factories;

use AmoCRM\Models\NoteModel;
use AmoCRM\Models\NoteType\AmoMailMessageNote;
use AmoCRM\Models\NoteType\AttachmentNote;
use AmoCRM\Models\NoteType\CallInNote;
use AmoCRM\Models\NoteType\CallOutNote;
use AmoCRM\Models\NoteType\ChatNote;
use AmoCRM\Models\NoteType\CommonNote;
use AmoCRM\Models\NoteType\CompanyCreatedNote;
use AmoCRM\Models\NoteType\ContactCreatedNote;
use AmoCRM\Models\NoteType\CustomerCreatedNote;
use AmoCRM\Models\NoteType\CustomerStatusChangedNote;
use AmoCRM\Models\NoteType\DpTagsAddedNote;
use AmoCRM\Models\NoteType\DpTagsRemovedNote;
use AmoCRM\Models\NoteType\DropboxNote;
use AmoCRM\Models\NoteType\ExtendedServiceMessageNote;
use AmoCRM\Models\NoteType\ExternalAttachmentNote;
use AmoCRM\Models\NoteType\FollowedLinkNote;
use AmoCRM\Models\NoteType\GeolocationNote;
use AmoCRM\Models\NoteType\InvoicePaidNote;
use AmoCRM\Models\NoteType\LeadAutoCreatedNote;
use AmoCRM\Models\NoteType\LeadCreatedNote;
use AmoCRM\Models\NoteType\LeadStatusChangedNote;
use AmoCRM\Models\NoteType\MentionNote;
use AmoCRM\Models\NoteType\MessageCashierNote;
use AmoCRM\Models\NoteType\OldCallNote;
use AmoCRM\Models\NoteType\OldMailMessageAttachmentNote;
use AmoCRM\Models\NoteType\OldMailMessageNote;
use AmoCRM\Models\NoteType\ServiceMessageNote;
use AmoCRM\Models\NoteType\SiteVisitNote;
use AmoCRM\Models\NoteType\SmsInNote;
use AmoCRM\Models\NoteType\SmsOutNote;
use AmoCRM\Models\NoteType\TargetingInNote;
use AmoCRM\Models\NoteType\TargetingOutNote;
use AmoCRM\Models\NoteType\TransactionNote;
use AmoCRM\Models\NoteType\ZoomMeetingNote;

class NoteFactory
{
    const NOTE_TYPE_CODE_LEAD_CREATED = 'lead_created';
    const NOTE_TYPE_CODE_CONTACT_CREATED = 'contact_created';
    const NOTE_TYPE_CODE_LEAD_STATUS_CHANGED = 'lead_status_changed';
    const NOTE_TYPE_CODE_COMMON = 'common';
    const NOTE_TYPE_CODE_ATTACHMENT = 'attachment';
    /** @deprecated */
    const NOTE_TYPE_CODE_CALL = 'call';
    /** @deprecated */
    const NOTE_TYPE_CODE_MAIL_MESSAGE = 'mail_message';
    /** @deprecated */
    const NOTE_TYPE_CODE_MAIL_MESSAGE_ATTACHMENT = 'mail_message_attachment';
    /** @deprecated */
    const NOTE_TYPE_CODE_EXTERNAL_ATTACH = 'external_attach';
    const NOTE_TYPE_CODE_CALL_IN = 'call_in';
    const NOTE_TYPE_CODE_CALL_OUT = 'call_out';
    const NOTE_TYPE_CODE_COMPANY_CREATED = 'company_created';
    const NOTE_TYPE_CODE_AMOMAIL_MESSAGE = 'amomail_message';
    /** @deprecated */
    const NOTE_TYPE_CODE_CHAT = 'chat';
    const NOTE_TYPE_CODE_SITE_VISIT = 'site_visit';
    const NOTE_TYPE_CODE_TARGETING_IN = 'targeting_in';
    const NOTE_TYPE_CODE_TARGETING_OUT = 'targeting_out';
    const NOTE_TYPE_CODE_LEAD_AUTO_CREATED = 'lead_auto_created';
    const NOTE_TYPE_CODE_CUSTOMER_CREATED = 'customer_created';
    const NOTE_TYPE_CODE_TRANSACTION = 'transaction';
    const NOTE_TYPE_CODE_SERVICE_MESSAGE = 'service_message';
    const NOTE_TYPE_CODE_DP_TAGS_ADDED = 'dp_tags_added';
    const NOTE_TYPE_CODE_DP_TAGS_REMOVED = 'dp_tags_removed';
    const NOTE_TYPE_CODE_MESSAGE_CASHIER = 'message_cashier';
    const NOTE_TYPE_CODE_INVOICE_PAID = 'invoice_paid';
    const NOTE_TYPE_CODE_GEOLOCATION = 'geolocation';
    const NOTE_TYPE_CODE_SMS_IN = 'sms_in';
    const NOTE_TYPE_CODE_SMS_OUT = 'sms_out';
    /** @deprecated */
    const NOTE_TYPE_CODE_CUSTOMER_STATUS_CHANGED = 'customer_status_changed';
    const NOTE_TYPE_CODE_EXTENDED_SERVICE_MESSAGE = 'extended_service_message';
    const NOTE_TYPE_CODE_ZOOM_MEETING = 'zoom_meeting';
    const NOTE_TYPE_CODE_MENTION = 'mention';
    const NOTE_TYPE_CODE_DROPBOX = 'dropbox_attachment';
    const NOTE_TYPE_LINK_FOLLOWED = 'link_followed';

    public static function createForType(string $type, array $note)
    {
        switch ($type) {
            case self::NOTE_TYPE_CODE_LEAD_CREATED:
                return (new LeadCreatedNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_CONTACT_CREATED:
                return (new ContactCreatedNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_LEAD_STATUS_CHANGED:
                return (new LeadStatusChangedNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_COMMON:
                return (new CommonNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_ATTACHMENT:
                return (new AttachmentNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_CALL:
                return (new OldCallNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_MAIL_MESSAGE:
                return (new OldMailMessageNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_MAIL_MESSAGE_ATTACHMENT:
                return (new OldMailMessageAttachmentNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_EXTERNAL_ATTACH:
                return (new ExternalAttachmentNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_CALL_IN:
                return (new CallInNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_CALL_OUT:
                return (new CallOutNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_COMPANY_CREATED:
                return (new CompanyCreatedNote())->fromArray($note);
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
            case self::NOTE_TYPE_CODE_LEAD_AUTO_CREATED:
                return (new LeadAutoCreatedNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_CUSTOMER_CREATED:
                return (new CustomerCreatedNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_TRANSACTION:
                return (new TransactionNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_SERVICE_MESSAGE:
                return (new ServiceMessageNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_DP_TAGS_ADDED:
                return (new DpTagsAddedNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_DP_TAGS_REMOVED:
                return (new DpTagsRemovedNote())->fromArray($note);
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
            case self::NOTE_TYPE_CODE_CUSTOMER_STATUS_CHANGED:
                return (new CustomerStatusChangedNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_EXTENDED_SERVICE_MESSAGE:
                return (new ExtendedServiceMessageNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_ZOOM_MEETING:
                return (new ZoomMeetingNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_MENTION:
                return (new MentionNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_CODE_DROPBOX:
                return (new DropboxNote())->fromArray($note);
                break;
            case self::NOTE_TYPE_LINK_FOLLOWED:
                return (new FollowedLinkNote())->fromArray($note);
                break;
            default:
                return (new NoteModel())->fromArray($note);
                break;
        }
    }
}
