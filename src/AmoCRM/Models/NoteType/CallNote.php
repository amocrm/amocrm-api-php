<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\NoteModel;

abstract class CallNote extends NoteModel
{
    public const CALL_STATUS_LEAVE_MESSAGE = 1;
    public const CALL_STATUS_SUCCESS_RECALL = 2;
    public const CALL_STATUS_SUCCESS_NOT_IN_STOCK = 3;
    public const CALL_STATUS_SUCCESS_CONVERSATION = 4;
    public const CALL_STATUS_FAIL_WRONG_NUMBER = 5;
    public const CALL_STATUS_FAIL_NOT_PHONED = 6;
    public const CALL_STATUS_FAIL_BUSY = 7;
    public const CALL_STATUS_UNDEFINED = 8;

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
     * @var null|string
     */
    protected $uniq;

    /**
     * @var null|string
     */
    protected $source;

    /**
     * @var int|string
     */
    protected $duration;

    /**
     * @var null|string
     */
    protected $link;

    /**
     * @var null|string
     */
    protected $phone;

    /**
     * @var null|string
     */
    protected $callResult;

    /**
     * @var null|int
     */
    protected $callStatus;

    /**
     * @param array $note
     *
     * @return self
     */
    public function fromArray(array $note): NoteModel
    {
        $model = parent::fromArray($note);

        if (isset($note['params']['uniq'])) {
            $this->setUniq($note['params']['uniq']);
        }

        if (isset($note['params']['duration'])) {
            $this->setDuration($note['params']['duration']);
        }

        if (isset($note['params']['source'])) {
            $this->setSource($note['params']['source']);
        }

        if (isset($note['params']['link'])) {
            $this->setLink($note['params']['link']);
        }

        if (isset($note['params']['phone'])) {
            $this->setPhone($note['params']['phone']);
        }

        if (isset($note['params']['call_result'])) {
            $this->setCallResult($note['params']['call_result']);
        }

        if (isset($note['params']['call_status'])) {
            $this->setCallStatus($note['params']['call_status']);
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
     * @param int|null $requestId
     * @return array
     */
    public function toApi(int $requestId = null): array
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


    /**
     * @return string|null
     */
    public function getUniq(): ?string
    {
        return $this->uniq;
    }

    /**
     * @param string|null $uniq
     * @return CallNote
     */
    public function setUniq(?string $uniq): CallNote
    {
        $this->uniq = $uniq;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * @param string|null $source
     * @return CallNote
     */
    public function setSource(?string $source): CallNote
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return int|string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int|string $duration
     * @return CallNote
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string|null $link
     * @return CallNote
     */
    public function setLink(?string $link): CallNote
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return CallNote
     */
    public function setPhone(?string $phone): CallNote
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCallResult(): ?string
    {
        return $this->callResult;
    }

    /**
     * @param string|null $callResult
     * @return CallNote
     */
    public function setCallResult(?string $callResult): CallNote
    {
        $this->callResult = $callResult;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCallStatus(): ?int
    {
        return $this->callStatus;
    }

    /**
     * @param int|null $callStatus
     * @return CallNote
     */
    public function setCallStatus(?int $callStatus): CallNote
    {
        if (!in_array($callStatus, self::AVAILABLE_CALL_STATUSES, true)) {
            return $this;
        }

        $this->callStatus = $callStatus;

        return $this;
    }
}
