<?php

namespace AmoCRM\Models\AccountSettings;

use AmoCRM\Models\BaseApiModel;
use Illuminate\Contracts\Support\Arrayable;

class Bot extends BaseApiModel implements Arrayable
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var null|string
     */
    protected $login;

    /**
     * @var bool
     */
    protected $isActive;

    /**
     * @var string
     */
    protected $photoUrl;

    /**
     * @var array|null
     */
    protected $directChat;

    /**
     * @param array $bot
     * @return self
     */
    public static function fromArray(array $bot): self
    {
        $model = new self();

        $model->setId($bot['id']);
        $model->setName($bot['name']);
        $model->setLogin($bot['login']);
        $model->setIsActive($bot['active']);
        $model->setPhotoUrl($bot['photo_url']);
        $model->setDirectChat($bot['direct_chat']);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'login' => $this->getLogin(),
            'active' => $this->isActive(),
            'photo_url' => $this->getPhotoUrl(),
            'direct_chat' => $this->getDirectChat()
        ];

        return $result;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @param string|null $login
     */
    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return string
     */
    public function getPhotoUrl(): string
    {
        return $this->photoUrl;
    }

    /**
     * @param string $photoUrl
     */
    public function setPhotoUrl(string $photoUrl): void
    {
        $this->photoUrl = $photoUrl;
    }

    /**
     * @return array|null
     */
    public function getDirectChat(): array
    {
        return $this->directChat;
    }

    /**
     * @param array|null $directChat
     */
    public function setDirectChat(?array $directChat = null): void
    {
        $this->directChat = $directChat;
    }

    /**
     * @param int|null $requestId
     * @return array
     */
    public function toApi(int $requestId = null): array
    {
        return [];
    }
}
