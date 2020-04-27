<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Models\NoteModel;

class SiteVisitNote extends NoteModel
{
    protected $modelClass = SiteVisitNote::class;

    /**
     * @var null|string
     */
    protected $url;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_SITE_VISIT;
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
     */
    public function toApi(int $requestId = null): array
    {
        $result = parent::toApi($requestId);

        $result['params']['url'] = $this->getUrl();

        return $result;
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
     * @return SiteVisitNote
     */
    public function setUrl(?string $url): SiteVisitNote
    {
        $this->url = $url;

        return $this;
    }
}
