<?php

namespace AmoCRM\Models\Interfaces;

/**
 * Interface CallInterface
 *
 * @package AmoCRM\Models\Interfaces
 */
interface CallInterface
{
    public const CALL_STATUS_LEAVE_MESSAGE = 1;
    public const CALL_STATUS_SUCCESS_RECALL = 2;
    public const CALL_STATUS_SUCCESS_NOT_IN_STOCK = 3;
    public const CALL_STATUS_SUCCESS_CONVERSATION = 4;
    public const CALL_STATUS_FAIL_WRONG_NUMBER = 5;
    public const CALL_STATUS_FAIL_NOT_PHONED = 6;
    public const CALL_STATUS_FAIL_BUSY = 7;
    public const CALL_STATUS_UNDEFINED = 8;
    public const AVAILABLE_CALL_STATUSES = [
        self::CALL_STATUS_LEAVE_MESSAGE,
        self::CALL_STATUS_SUCCESS_RECALL,
        self::CALL_STATUS_SUCCESS_NOT_IN_STOCK,
        self::CALL_STATUS_SUCCESS_CONVERSATION,
        self::CALL_STATUS_FAIL_WRONG_NUMBER,
        self::CALL_STATUS_FAIL_NOT_PHONED,
        self::CALL_STATUS_FAIL_BUSY,
        self::CALL_STATUS_UNDEFINED,
    ];

    public const CALL_DIRECTION_IN = 'inbound';
    public const CALL_DIRECTION_OUT = 'outbound';
}
