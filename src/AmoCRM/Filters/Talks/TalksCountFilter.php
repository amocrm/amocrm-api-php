<?php

declare(strict_types=1);

namespace AmoCRM\Filters\Talks;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Filters\BaseEntityFilter;

class TalksCountFilter extends BaseEntityFilter
{
    public const TALK_STATUS_OPENED = 'opened';
    public const TALK_READ_STATUS_UNREAD = 'false';
    private const ALLOWED_TALK_STATUSES = [
        self::TALK_STATUS_OPENED,
    ];
    private const ALLOWED_TALK_READ_STATUSES = [
        self::TALK_READ_STATUS_UNREAD,
    ];

    /**
     * @var string
     */
    private $talkStatus = self::TALK_STATUS_OPENED;

    /**
     * @var string
     */
    private $talkReadStatus = self::TALK_READ_STATUS_UNREAD;

    /**
     * @param string $talkStatus
     *
     * @return TalksCountFilter
     * @throws InvalidArgumentException
     */
    public function setTalkStatus(string $talkStatus): TalksCountFilter
    {
        if (!in_array($talkStatus, self::ALLOWED_TALK_STATUSES, true)) {
            throw new InvalidArgumentException(sprintf(
                'Filtering by talk status %s is not supported',
                $talkStatus
            ));
        }

        $this->talkStatus = $talkStatus;

        return $this;
    }

    /**
     * @return string
     */
    public function getTalkStatus(): string
    {
        return $this->talkStatus;
    }

    /**
     * @param string $talkReadStatus
     *
     * @return TalksCountFilter
     * @throws InvalidArgumentException
     */
    public function setTalkReadStatus(string $talkReadStatus): TalksCountFilter
    {
        if (!in_array($talkReadStatus, self::ALLOWED_TALK_READ_STATUSES, true)) {
            throw new InvalidArgumentException(sprintf(
                'Filtering by talk read status %s is not supported',
                $talkReadStatus
            ));
        }

        $this->talkReadStatus = $talkReadStatus;

        return $this;
    }

    /**
     * @return string
     */
    public function getTalkReadStatus(): string
    {
        return $this->talkReadStatus;
    }

    /**
     * @return array
     */
    public function buildFilter(): array
    {
        $filter = [];

        $filter['filter']['status'] = $this->getTalkStatus();
        $filter['filter']['is_read'] = $this->getTalkReadStatus();

        return $filter;
    }
}
