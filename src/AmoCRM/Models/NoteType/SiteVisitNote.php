<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;
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
     */
    public function toApi(?string $requestId = "0"): array
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
