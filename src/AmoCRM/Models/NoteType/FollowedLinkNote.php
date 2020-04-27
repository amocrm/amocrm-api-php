<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\NoteModel;

class FollowedLinkNote extends NoteModel
{
    protected $modelClass = FollowedLinkNote::class;

    /**
     * @var null|string
     */
    protected $url;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_LINK_FOLLOWED;
    }

    /**
     * @param array $note
     *
     * @return self
     */
    public function fromArray(array $note): NoteModel
    {
        $model = parent::fromArray($note);

        if (isset($note['params']['url'])) {
            $this->setUrl($note['params']['url']);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['params']['url'] = $this->getUrl();

        return $result;
    }

    /**
     * @param int|null $requestId
     * @return array
     * @throws NotAvailableForActionException
     */
    public function toApi(int $requestId = null): array
    {
        throw new NotAvailableForActionException();
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return FollowedLinkNote
     */
    public function setUrl(?string $url): FollowedLinkNote
    {
        $this->url = $url;

        return $this;
    }
}
