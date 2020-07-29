<?php

namespace AmoCRM\Models;

use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\UsersCollection;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Rights\RightModel;
use AmoCRM\Models\Traits\RequestIdTrait;
use InvalidArgumentException;

use function is_null;

class RoleModel extends BaseApiModel implements HasIdInterface
{
    use RequestIdTrait;

    public const USERS = 'users';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var UsersCollection
     */
    protected $users;

    /**
     * @var RightModel
     */
    protected $rights;

    /**
     * @param array $role
     *
     * @return self
     */
    public static function fromArray(array $role): self
    {
        if (empty($role['id'])) {
            throw new InvalidArgumentException('Role id is empty in ' . json_encode($role));
        }

        $roleModel = new self();

        $roleModel
            ->setId($role['id'])
            ->setName($role['name'])
            ->setRights(RightModel::fromArray($role['rights']));

        $usersCollection = new UsersCollection();
        if (!empty($role[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::USERS])) {
            $usersCollection = $usersCollection->fromArray($role[AmoCRMApiRequest::EMBEDDED][EntityTypesInterface::USERS]);
        }
        $roleModel->setUsers($usersCollection);


        return $roleModel;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'right' => $this->getRights()->toArray(),
            'users' => is_null($this->getUsers()) ? null : $this->getUsers()->toArray(),
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
     * @return RoleModel
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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
     * @return RoleModel
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|UsersCollection
     */
    public function getUsers(): ?UsersCollection
    {
        return $this->users;
    }

    /**
     * @param UsersCollection $users
     * @return RoleModel
     */
    public function setUsers(UsersCollection $users): self
    {
        $this->users = $users;

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
     * @return RoleModel
     */
    public function setRights(RightModel $rights): self
    {
        $this->rights = $rights;

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

        if (!is_null($this->getRights())) {
            $result['rights'] = $this->getRights()->toApi();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }

    public static function getAvailableWith(): array
    {
        return [
            self::USERS,
        ];
    }
}
