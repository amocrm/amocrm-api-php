<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;
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
        return NoteFactory::NOTE_TYPE_CODE_LINK_FOLLOWED;
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
            $model->setUrl($note['params']['url']);
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
     * @param string|null $requestId
     * @return array
     * @throws NotAvailableForActionException
     */
    public function toApi(?string $requestId = "0"): array
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
