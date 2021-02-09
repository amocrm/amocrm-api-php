<?php

namespace AmoCRM\Models\SocialProfiles;

use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\BaseApiModel;

/**
 * Class SocialProfileModel
 *
 * @package AmoCRM\Models\SocialProfiles
 */
class SocialProfileModel extends BaseApiModel
{
    /**
     * @var string|null
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $avatar;

    /**
     * @var string|null
     */
    protected $sourceName;

    /**
     * @var array|null
     */
    protected $data;

    /**
     * @param array $socialProfile
     *
     * @return self
     */
    public static function fromArray(array $socialProfile): self
    {
        $model = new static();

        $model
            ->setId($socialProfile['id'])
            ->setName($socialProfile['name'])
            ->setAvatar($socialProfile['avatar'])
            ->setSourceName($socialProfile['source_name'])
            ->setData($socialProfile['data']);

        return $model;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     *
     * @return SocialProfileModel
     */
    public function setId(?string $id): SocialProfileModel
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return SocialProfileModel
     */
    public function setName(?string $name): SocialProfileModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string|null $avatar
     *
     * @return SocialProfileModel
     */
    public function setAvatar(?string $avatar): SocialProfileModel
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSourceName(): ?string
    {
        return $this->sourceName;
    }

    /**
     * @param string|null $sourceName
     *
     * @return SocialProfileModel
     */
    public function setSourceName(?string $sourceName): SocialProfileModel
    {
        $this->sourceName = $sourceName;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param array|null $data
     *
     * @return SocialProfileModel
     */
    public function setData(?array $data): SocialProfileModel
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'avatar' => $this->getAvatar(),
            'source_name' => $this->getSourceName(),
            'data' => $this->getData(),
        ];
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
}
