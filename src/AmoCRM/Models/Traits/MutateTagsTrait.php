<?php

declare(strict_types=1);

namespace AmoCRM\Models\Traits;

/**
 * Trait TagsTrait
 *
 * @package AmoCRM\Models\Traits
 */
trait MutateTagsTrait
{
    /**
     * @var array|null
     */
    protected $tagsToAdd = null;

    /**
     * @var array|null
     */
    protected $tagsToDelete = null;


    /**
     * @return array|null
     */
    public function getTagsToAdd(): ?array
    {
        return $this->tagsToAdd;
    }

    /**
     * @param array|null $tagsToAdd
     *
     * @return self
     */
    public function setTagsToAdd(?array $tagsToAdd): self
    {
        $this->tagsToAdd = $tagsToAdd;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getTagsToDelete(): ?array
    {
        return $this->tagsToDelete;
    }

    /**
     * @param array|null $tagsToDelete
     *
     * @return self
     */
    public function setTagsToDelete(?array $tagsToDelete): self
    {
        $this->tagsToDelete = $tagsToDelete;

        return $this;
    }

    /**
     * При получении сущности из API, она приходит вместе с _embedded[tags]. Поэтому если в него засетить
     * tagsToDelete или tagsToAdd, то в запросе на обновление оно уйдет вместе с _embedded[tags] и в приоритете
     * будут теги из _embedded[tags] и tagsToAdd/tagsToDelete не будут учтены.
     *
     * @param array $entity
     *
     * @return array
     */
    public function mutateTags(array $entity): array
    {
        if (!is_null($this->getTagsToAdd())) {
            $entity['tags_to_add'] = $this->getTagsToAdd();
        }

        if (!is_null($this->getTagsToDelete())) {
            $entity['tags_to_delete'] = $this->getTagsToDelete();
        }
        $this->setTags(null);

        return $entity;
    }
}
