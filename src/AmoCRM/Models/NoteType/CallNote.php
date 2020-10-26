<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\NoteModel;
use AmoCRM\Models\Traits\CallTrait;

use function is_string;

abstract class CallNote extends NoteModel
{
    use CallTrait;

    /** @deprecated */
    public const CALL_STATUS_LEAVE_MESSAGE = 1;
    /** @deprecated */
    public const CALL_STATUS_SUCCESS_RECALL = 2;
    /** @deprecated */
    public const CALL_STATUS_SUCCESS_NOT_IN_STOCK = 3;
    /** @deprecated */
    public const CALL_STATUS_SUCCESS_CONVERSATION = 4;
    /** @deprecated */
    public const CALL_STATUS_FAIL_WRONG_NUMBER = 5;
    /** @deprecated */
    public const CALL_STATUS_FAIL_NOT_PHONED = 6;
    /** @deprecated */
    public const CALL_STATUS_FAIL_BUSY = 7;
    /** @deprecated */
    public const CALL_STATUS_UNDEFINED = 8;

    /** @deprecated */
    protected const AVAILABLE_CALL_STATUSES = [
        self::CALL_STATUS_LEAVE_MESSAGE,
        self::CALL_STATUS_SUCCESS_RECALL,
        self::CALL_STATUS_SUCCESS_NOT_IN_STOCK,
        self::CALL_STATUS_SUCCESS_CONVERSATION,
        self::CALL_STATUS_FAIL_WRONG_NUMBER,
        self::CALL_STATUS_FAIL_NOT_PHONED,
        self::CALL_STATUS_FAIL_BUSY,
        self::CALL_STATUS_UNDEFINED,
    ];

    /**
     * @param array $note
     *
     * @return self
     */
    public function fromArray(array $note): NoteModel
    {
        $model = parent::fromArray($note);

        if (isset($note['params']['uniq']) && is_string($note['params']['uniq'])) {
            $model->setUniq($note['params']['uniq']);
        }

        if (isset($note['params']['duration'])) {
            $model->setDuration($note['params']['duration']);
        }

        if (isset($note['params']['source'])) {
            $model->setSource($note['params']['source']);
        }

        if (isset($note['params']['link'])) {
            $model->setLink($note['params']['link']);
        }

        if (isset($note['params']['phone'])) {
            $model->setPhone($note['params']['phone']);
        }

        if (isset($note['params']['call_result'])) {
            $model->setCallResult($note['params']['call_result']);
        }

        if (isset($note['params']['call_status'])) {
            $model->setCallStatus($note['params']['call_status']);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['params'] = [
            'uniq' => $this->getUniq(),
            'duration' => $this->getDuration(),
            'source' => $this->getSource(),
            'link' => $this->getLink(),
            'phone' => $this->getPhone(),
            'call_result' => $this->getCallResult(),
            'call_status' => $this->getCallStatus(),
        ];

        return $result;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = parent::toApi($requestId);

        $result['params'] = [
            'uniq' => $this->getUniq(),
            'duration' => $this->getDuration(),
            'source' => $this->getSource(),
            'link' => $this->getLink(),
            'phone' => $this->getPhone(),
            'call_result' => $this->getCallResult(),
            'call_status' => $this->getCallStatus(),
        ];

        return $result;
    }
}
