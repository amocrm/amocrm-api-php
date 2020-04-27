<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\NoteModel;

abstract class DpTagsNote extends NoteModel
{
    /**
     * @var null|string
     */
    protected $tags;

    /**
     * @param array $note
     *
     * @return self
     */
    public function fromArray(array $note): NoteModel
    {
        $model = parent::fromArray($note);

        if (isset($note['params']['tags'])) {
            $this->setTags($note['params']['tags']);
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
            'tags' => $this->getTags(),
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
            'tags' => $this->getTags(),
        ];

        return $result;
    }


    /**
     * @return string|null
     */
    public function getTags(): ?string
    {
        return $this->tags;
    }

    /**
     * @param array|null $tags
     * @return DpTagsNote
     */
    public function setTags(?array $tags): DpTagsNote
    {
        $this->tags = $tags;

        return $this;
    }
}
