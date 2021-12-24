<?php

declare(strict_types=1);

namespace AmoCRM\Models\Talks;

/**
 * Модель для закрытия беседы
 */
class TalkCloseActionModel
{
    /** @var int */
    protected $talkId;
    /**
     * Если выставлен true - закрыть беседу без запуска NPS-бота
     *
     * @var bool
     */
    protected $forceClose = false;

    public function __construct(int $talkId, bool $forceClose = false)
    {
        $this->talkId = $talkId;
        $this->forceClose = $forceClose;
    }

    public function getTalkId(): int
    {
        return $this->talkId;
    }

    public function setTalkId(int $talkId): self
    {
        $this->talkId = $talkId;

        return $this;
    }

    public function isForceClose(): bool
    {
        return $this->forceClose;
    }

    public function setForceClose(bool $forceClose): self
    {
        $this->forceClose = $forceClose;

        return $this;
    }
}
