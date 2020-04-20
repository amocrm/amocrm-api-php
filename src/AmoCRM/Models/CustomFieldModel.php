<?php

namespace AmoCRM\Models;

use InvalidArgumentException;

class CustomFieldModel extends BaseApiModel
{
    const GROUP_ID = 'group_id';
    const ENUMS = 'enums';
    const REQUIRED_STATUSES = 'required_statuses';

    const TYPE_TEXT = 'text';
    const TYPE_NUMERIC = 'numeric';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_SELECT = 'select';
    const TYPE_MULTISELECT = 'multiselect';
    const TYPE_DATE = 'date';
    const TYPE_URL = 'url';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_RADIOBUTTON = 'radiobutton';
    const TYPE_SHORT_ADDRESS = 'streetaddress';
    const TYPE_SMART_ADDRESS = 'smart_address';
    const TYPE_BIRTHDAY = 'birthday';
    const TYPE_LEGAL_ENTITY = 'legal_entity';
    const TYPE_DATE_TIME = 'date_time';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     * //TODO validate
     */
    protected $type;

    /**
     * @var null|string
     */
    protected $groupId;

    /**
     * @var int
     */
    protected $sort;

    /**
     * @var null|bool
     */
    protected $isApiOnly;

    /**
     * @var null|int
     * //TODO только для дня рождения
     * //TODO константы и валидация
     */
    protected $remind;

    /**
     * @var null|array
     * //TODO заменить на модель
     * //TODO только для каталогов
     */
    protected $settings;

    /**
     * @var null|array
     * //TODO заменить на коллекцию
     */
    protected $requiredStatuses;

    /**
     * @var null|array
     * //TODO заменить на коллекцию
     */
    protected $enums;

    /**
     * @var int
     */
    protected $requestId;

    /**
     * @param array $customField
     *
     * @return self
     */
    public static function fromArray(array $customField): self
    {
        if (empty($customField['id'])) {
            throw new InvalidArgumentException('Custom field id is empty in ' . json_encode($customField));
        }

        $customFieldModel = new self();

        $customFieldModel
            ->setId($customField['id'])
            ->setName($customField['name'])
            ->setType($customField['type'])
            ->setSort($customField['sort'])
            ->setIsApiOnly($customField['is_api_only']);

        if (!empty($customField['group_id'])) {
            $customFieldModel->setGroupId($customField['group_id']);
        }

        if (!empty($customField['required_statuses'])) {
            $customFieldModel->setRequiredStatuses($customField['required_statuses']);
        }

        if (!empty($customField['remind'])) {
            $customFieldModel->setRemind($customField['remind']);
        }

        if (!empty($customField['enums'])) {
            $customFieldModel->setEnums($customField['enums']);
        }

        if (!empty($customField['settings'])) {
            $customFieldModel->setSettings($customField['settings']);
        }

        return $customFieldModel;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        //todo
        $result = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'sort' => $this->getSort(),
        ];

        return $result;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return CustomFieldModel
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
     * @return CustomFieldModel
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getSort(): ?int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     * @return CustomFieldModel
     */
    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @param int|null $requestId
     * @return array
     */
    public function toApi(int $requestId = null): array
    {
        //todo
        $result = [];

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        if (!is_null($this->getName())) {
            $result['name'] = $this->getName();
        }

        if (!is_null($this->getSort())) {
            $result['sort'] = $this->getSort();
        }

        if (!is_null($this->getType())) {
            $result['type'] = $this->getType();
        }

        if (!is_null($this->getGroupId())) {
            $result['group_id'] = $this->getGroupId();
        }

        if (!is_null($this->getIsApiOnly())) {
            $result['is_api_only'] = $this->getIsApiOnly();
        }

        if (!is_null($this->getRequiredStatuses())) {
            $result['required_statuses'] = $this->getRequiredStatuses();
        }

        if (!is_null($this->getRemind())) {
            $result['remind'] = $this->getRemind();
        }

        if (!is_null($this->getEnums())) {
            $result['enums'] = $this->getEnums();
        }

        if (!is_null($this->getSettings())) {
            $result['settings'] = $this->getSettings();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId + 1); //Бага в API не принимает 0
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }


    /**
     * @return int|null
     */
    public function getRequestId(): ?int
    {
        return $this->requestId;
    }

    /**
     * @param int|null $requestId
     * @return CustomFieldModel
     */
    public function setRequestId(?int $requestId): self
    {
        $this->requestId = $requestId;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getGroupId(): ?string
    {
        return $this->groupId;
    }

    /**
     * @param string $groupId
     * @return CustomFieldModel
     */
    public function setGroupId(string $groupId): CustomFieldModel
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return CustomFieldModel
     */
    public function setType(string $type): CustomFieldModel
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function getIsApiOnly(): ?bool
    {
        return $this->isApiOnly;
    }

    /**
     * @param bool $isApiOnly
     * @return CustomFieldModel
     */
    public function setIsApiOnly(bool $isApiOnly): CustomFieldModel
    {
        if ($isApiOnly) {
            //todo collection
            $this->setRequiredStatuses([]);
        }
        $this->isApiOnly = $isApiOnly;

        return $this;
    }

    /**
     * @return null|array
     */
    public function getRequiredStatuses(): ?array
    {
        return $this->requiredStatuses;
    }

    /**
     * @param array $requiredStatuses
     * @return CustomFieldModel
     */
    public function setRequiredStatuses(array $requiredStatuses): CustomFieldModel
    {
        $this->requiredStatuses = $requiredStatuses;

        return $this;
    }

    /**
     * @return null|array
     */
    public function getEnums(): ?array
    {
        return $this->enums;
    }

    /**
     * @param array $enums
     * @return CustomFieldModel
     */
    public function setEnums(array $enums): CustomFieldModel
    {
        $this->enums = $enums;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getRemind(): ?int
    {
        return $this->remind;
    }

    /**
     * @param int $remind
     * @return CustomFieldModel
     */
    public function setRemind(int $remind): CustomFieldModel
    {
        $this->remind = $remind;

        return $this;
    }

    /**
     * @return null|array
     */
    public function getSettings(): ?array
    {
        return $this->settings;
    }

    /**
     * @param array $settings
     * @return CustomFieldModel
     */
    public function setSettings(array $settings): CustomFieldModel
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * @return array
     */
    public static function getAvailableWith(): array
    {
        return [
            self::GROUP_ID,
            self::ENUMS,
            self::REQUIRED_STATUSES,
        ];
    }
}
