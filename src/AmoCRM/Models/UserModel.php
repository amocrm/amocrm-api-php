<?php

namespace AmoCRM\Models;

use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\RolesCollection;
use AmoCRM\Collections\UsersGroupsCollection;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Rights\RightModel;
use AmoCRM\Models\Traits\RequestIdTrait;
use InvalidArgumentException;

use function is_null;

class UserModel extends BaseApiModel implements HasIdInterface
{
    use RequestIdTrait;

    /** @var string Информация о роли пользователя */
    public const ROLE = 'role';

    /** @var string Информация о группе пользователя */
    public const GROUP = 'group';

    /** @var string amoJo ID пользователя */
    public const AMOJO_ID = 'amojo_id';

    /** @var string UUID пользователя */
    public const UUID = 'uuid';

    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $uuid;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $amojoId;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $lang;

    /**
     * @var RightModel
     */
    protected $rights;

    /**
     * @var RolesCollection|null
     */
    protected $roles;

    /**
     * @var UsersGroupsCollection|null
     */
    protected $groups;

    /**
     * @var string|null
     */
    protected $password;

    /**
     * @param array $user
     *
     * @return self
     */
    public static function fromArray(array $user): self
    {
        if (empty($user['id'])) {
            throw new InvalidArgumentException('User id is empty in ' . json_encode($user));
        }

        $model = new self();

        $model
            ->setId($user['id'])
            ->setName($user['name'] ?? null)
            ->setEmail($user['email'])
            ->setLang($user['lang'] ?? null)
            ->setUuid($user['uuid'] ?? null)
            ->setAmojoId($user['amojo_id'] ?? null)
            ->setRights(RightModel::fromArray($user['rights']));

        $groupsCollection = new UsersGroupsCollection();
        if (!empty($user[AmoCRMApiRequest::EMBEDDED]['groups'])) {
            $groupsCollection = $groupsCollection->fromArray($user[AmoCRMApiRequest::EMBEDDED]['groups']);
        }
        $model->setGroups($groupsCollection);

        $rolesCollection = new RolesCollection();
        if (!empty($user[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::USER_ROLES])) {
            $rolesCollection = $rolesCollection->fromArray($user[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::USER_ROLES]);
        }
        $model->setRoles($rolesCollection);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'lang' => $this->getLang(),
            'uuid' => $this->getUuid(),
            'amojo_id' => $this->getAmojoId(),
            'rights' => $this->getRights()->toArray(),
            'roles' => is_null($this->getRoles()) ? null : $this->getRoles()->toArray(),
            'groups' => is_null($this->getGroups()) ? null : $this->getGroups()->toArray(),
        ];
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return UserModel
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @param string|null $uuid
     *
     * @return UserModel
     */
    public function setUuid(?string $uuid): UserModel
    {
        $this->uuid = $uuid;

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
     * @return UserModel
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return RightModel
     */
    public function getRights(): RightModel
    {
        return $this->rights;
    }

    /**
     * @param RightModel $rights
     * @return UserModel
     */
    public function setRights(RightModel $rights): self
    {
        $this->rights = $rights;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     *
     * @return UserModel
     */
    public function setEmail(?string $email): UserModel
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLang(): ?string
    {
        return $this->lang;
    }

    /**
     * @param string|null $lang
     *
     * @return UserModel
     */
    public function setLang(?string $lang): UserModel
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @return RolesCollection|null
     */
    public function getRoles(): ?RolesCollection
    {
        return $this->roles;
    }

    /**
     * @param RolesCollection|null $roles
     *
     * @return UserModel
     */
    public function setRoles(?RolesCollection $roles): UserModel
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return UsersGroupsCollection|null
     */
    public function getGroups(): ?UsersGroupsCollection
    {
        return $this->groups;
    }

    /**
     * @param UsersGroupsCollection|null $groups
     *
     * @return UserModel
     */
    public function setGroups(?UsersGroupsCollection $groups): UserModel
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @return null|string
     */
    protected function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param null|string $password
     *
     * @return UserModel
     */
    public function setPassword(?string $password): UserModel
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAmojoId(): ?string
    {
        return $this->amojoId;
    }

    /**
     * @param string|null $amojoId
     *
     * @return UserModel
     */
    public function setAmojoId(?string $amojoId): UserModel
    {
        $this->amojoId = $amojoId;

        return $this;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = [];

        if (!is_null($this->getName())) {
            $result['name'] = $this->getName();
        }

        if (!is_null($this->getEmail())) {
            $result['email'] = $this->getEmail();
        }

        if (!is_null($this->getEmail())) {
            $result['email'] = $this->getEmail();
        }

        if (!is_null($this->getPassword())) {
            $result['password'] = $this->getPassword();
        }

        if (!is_null($this->getRights())) {
            $result['rights'] = $this->getRights()->toUsersApi();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }

    /**
     * @return array
     */
    public static function getAvailableWith(): array
    {
        return [
            self::ROLE,
            self::UUID,
            self::GROUP,
            self::AMOJO_ID,
        ];
    }
}
