<?php

namespace AmoCRM\Models;

use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Collections\BotsCollection;
use AmoCRM\Collections\TaskTypesCollection;
use AmoCRM\Collections\UsersGroupsCollection;
use AmoCRM\Models\AccountSettings\AmojoRights;
use AmoCRM\Models\AccountSettings\AmoMessenger;
use AmoCRM\Models\AccountSettings\DateTimeSettings;
use AmoCRM\Models\AccountSettings\NotificationsInfo;
use AmoCRM\Models\AccountSettings\Total;
use AmoCRM\Models\AccountSettings\AmojoUrl;
use Carbon\Carbon;

class AccountModel extends BaseApiModel
{
    const AMOJO_ID = 'amojo_id';
    const UUID = 'uuid';
    const NOTIFICATIONS_INFO = 'notifications_info';
    const AMOJO_URL = 'amojo_url';
    const AMOJO_RIGHTS = 'amojo_rights';
    const AMO_MESSENGER = 'amo_messenger';
    const USER_GROUPS = 'users_groups';
    const BOTS = 'bots';
    const TASK_TYPES = 'task_types';
    const TOTAL = 'total';
    const VERSION = 'version';
    const DATETIME_SETTINGS = 'datetime_settings';

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var Carbon */
    protected $createdAt;

    /** @var Carbon */
    protected $updatedAt;

    /** @var int */
    protected $updatedBy;

    /** @var int */
    protected $createdBy;

    /** @var string */
    protected $subdomain;

    /** @var string|null */
    protected $amojoId;

    /** @var string|null */
    protected $uuid;

    /** @var NotificationsInfo */
    protected $notificationInfo;

    /** @var AmojoRights */
    protected $amojoRights;

    /** @var AmojoUrl */
    protected $amojoUrl;

    /** @var AmoMessenger */
    protected $amoMessenger;

    /** @var UsersGroupsCollection */
    protected $usersGroups;

    /** @var TaskTypesCollection */
    protected $taskTypes;

    /** @var int */
    protected $version;

    /** @var bool */
    protected $unsortedOn;

    /** @var int */
    protected $mobileFeatureVersion;

    /** @var bool */
    protected $customersEnabled;

    /** @var bool */
    protected $periodicityEnabled;

    /** @var bool */
    protected $lossReasonsEnabled;

    /** @var bool */
    protected $helpbotEnabled;

    /** @var int */
    protected $contactNameDisplayOrder;

    /** @var DateTimeSettings */
    protected $datetimeSettings;

    /** @var int */
    protected $currentUserId;

    /** @var string|null */
    protected $country;

    /** @var Total|null */
    protected $total;

    /** @var BotsCollection */
    protected $bots;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
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
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return null|int
     */
    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    /**
     * @param null|int $userId
     *
     * @return self
     */
    public function setCreatedBy(?int $userId): self
    {
        $this->createdBy = $userId;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    /**
     * @param null|int $userId
     *
     * @return self
     */
    public function setUpdatedBy(?int $userId): self
    {
        $this->updatedBy = $userId;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    /**
     * @param Carbon $timestamp
     *
     * @return self
     */
    public function setCreatedAt(Carbon $timestamp): self
    {
        $this->createdAt = $timestamp;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    /**
     * @param Carbon $timestamp
     *
     * @return self
     */
    public function setUpdatedAt(Carbon $timestamp): self
    {
        $this->updatedAt = $timestamp;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubdomain(): string
    {
        return $this->subdomain;
    }

    /**
     * @param string $subdomain
     * @return $this
     */
    public function setSubdomain(string $subdomain): self
    {
        $this->subdomain = $subdomain;

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
     * @param string|null $id
     * @return $this
     */
    public function setAmojoId(?string $id): self
    {
        $this->amojoId = $id;

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
     * @param string|null $id
     * @return $this
     */
    public function setUuid(?string $id): self
    {
        $this->uuid = $id;

        return $this;
    }

    /**
     * @return null|NotificationsInfo
     */
    public function getNotificationsInfo(): ?NotificationsInfo
    {
        return $this->notificationInfo;
    }

    /**
     * @param null|NotificationsInfo $settings
     *
     * @return self
     */
    public function setNotificationsInfo(?NotificationsInfo $settings): self
    {
        $this->notificationInfo = $settings;

        return $this;
    }

    /**
     * @param array $account
     * @return static
     * @throws \Exception
     */
    public static function fromArray(array $account): self
    {
        $accountModel = new self();
        $accountModel->setId((int)$account['id'])
            ->setName($account['name'])
            ->setSubdomain($account['subdomain'])
            ->setCreatedBy((int)$account['created_by'])
            ->setCreatedAt(new Carbon($account['created_at']))
            ->setUpdatedBy((int)$account['updated_by'])
            ->setUpdatedAt(new Carbon($account['updated_at']))
            ->setCurrentUserId((int)$account['current_user_id'])
            ->setCountry((string)$account['country'])
            ->setUnsortedOn((bool)$account['is_unsorted_on'])
            ->setMobileFeatureVersion((int)$account['mobile_feature_version'])
            ->setCustomersEnabled((bool)$account['is_customers_enabled'])
            ->setPeriodicityEnabled((bool)$account['is_periodicity_enabled'])
            ->setLossReasonsEnabled((bool)$account['is_loss_reason_enabled'])
            ->setHelpbotEnabled((bool)$account['is_helpbot_enabled'])
            ->setContactNameDisplayOrder((int)$account['contact_name_display_order']);

        if (isset($account[self::AMOJO_ID])) {
            $accountModel->setAmojoId($account[self::AMOJO_ID]);
        }

        if (isset($account[self::UUID])) {
            $accountModel->setUuid($account[self::UUID]);
        }

        if (isset($account[AmoCRMApiRequest::EMBEDDED][self::NOTIFICATIONS_INFO])) {
            $notificationsInfo = $account[AmoCRMApiRequest::EMBEDDED][self::NOTIFICATIONS_INFO];

            $accountModel->setNotificationsInfo(new NotificationsInfo(
                $notificationsInfo['base_url'],
                $notificationsInfo['ws_url'],
                $notificationsInfo['ws_url_v2']
            ));
        }

        if (isset($account[AmoCRMApiRequest::EMBEDDED][self::AMOJO_URL])) {
            $accountModel->setAmojoUrl(new AmojoUrl(
                $account[AmoCRMApiRequest::EMBEDDED][self::AMOJO_URL]['base_url']
            ));
        }

        if (isset($account[AmoCRMApiRequest::EMBEDDED][self::AMOJO_RIGHTS])) {
            $accountModel->setAmojoRights(new AmojoRights(
                $account[AmoCRMApiRequest::EMBEDDED][self::AMOJO_RIGHTS]['can_direct'],
                $account[AmoCRMApiRequest::EMBEDDED][self::AMOJO_RIGHTS]['can_create_groups']
            ));
        }

        if (isset($account[AmoCRMApiRequest::EMBEDDED][self::AMO_MESSENGER])) {
            $accountModel->setAmoMessenger(new AmoMessenger(
                $account[AmoCRMApiRequest::EMBEDDED][self::AMO_MESSENGER]['enabled'],
                $account[AmoCRMApiRequest::EMBEDDED][self::AMO_MESSENGER]['wss_url'],
                $account[AmoCRMApiRequest::EMBEDDED][self::AMO_MESSENGER]['api_url']
            ));
        }

        if (isset($account[AmoCRMApiRequest::EMBEDDED][self::DATETIME_SETTINGS])) {
            $dateTimeSettings = $account[AmoCRMApiRequest::EMBEDDED][self::DATETIME_SETTINGS];

            $accountModel->setDatetimeSettings(new DateTimeSettings(
                $dateTimeSettings['date_pattern'],
                $dateTimeSettings['short_date_pattern'],
                $dateTimeSettings['short_time_pattern'],
                $dateTimeSettings['date_formant'],
                $dateTimeSettings['time_format'],
                $dateTimeSettings['timezone'],
                $dateTimeSettings['timezone_offset']
            ));
        }

        if (isset($account[AmoCRMApiRequest::EMBEDDED][self::USER_GROUPS])) {
            $userGroups = $account[AmoCRMApiRequest::EMBEDDED][self::USER_GROUPS];
            $collection = new UsersGroupsCollection();
            $collection = $collection->fromArray($userGroups);
            $accountModel->setUsersGroups($collection);
        }

        if (isset($account[AmoCRMApiRequest::EMBEDDED][self::BOTS])) {
            $bots = $account[AmoCRMApiRequest::EMBEDDED][self::BOTS];
            $collection = new BotsCollection();
            $collection = $collection->fromArray($bots);
            $accountModel->setBots($collection);
        }

        if (isset($account[AmoCRMApiRequest::EMBEDDED][self::TASK_TYPES])) {
            $taskTypes = $account[AmoCRMApiRequest::EMBEDDED][self::TASK_TYPES];
            $collection = new TaskTypesCollection();
            $collection = $collection->fromArray($taskTypes);
            $accountModel->setTaskTypes($collection);
        }

        if (isset($account[AmoCRMApiRequest::EMBEDDED][self::TOTAL])) {
            $total = $account[AmoCRMApiRequest::EMBEDDED][self::TOTAL];
            $accountModel->setTotal(new Total(
                $total['contacts'],
                $total['companies'],
                $total['leads'],
                $total['active_leads'],
                $total['notes'],
                $total['tasks']
            ));
        }

        return $accountModel;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'subdomain' => $this->getSubdomain(),
            'created_at' => $this->getCreatedAt()->timestamp,
            'created_by' => $this->getCreatedBy(),
            'updated_at' => $this->getUpdatedAt()->timestamp,
            'updated_by' => $this->getUpdatedBy(),
            'current_user_id' => $this->getCurrentUserId(),
            'country' => $this->getCountry(),
            'is_unsorted_on' => $this->isUnsortedOn(),
            'mobile_feature_version' => $this->getMobileFeatureVersion(),
            'is_customers_enabled' => $this->isCustomersEnabled(),
            'is_periodicity_enabled' => $this->isPeriodicityEnabled(),
            'is_loss_reason_enabled' => $this->isLossReasonsEnabled(),
            'is_helpbot_enabled' => $this->isHelpbotEnabled(),
            'contact_name_display_order' => $this->getContactNameDisplayOrder(),
        ];

        if (!is_null($this->getAmojoId())) {
            $result['amojo_id'] = $this->getAmojoId();
        }

        if (!is_null($this->getUuid())) {
            $result['uuid'] = $this->getUuid();
        }

        if (!is_null($this->getNotificationsInfo())) {
            $result['notifications_info'] = $this->getNotificationsInfo();
        }

        if (!is_null($this->getAmojoUrl())) {
            $result['amojo_url'] = $this->getAmojoUrl();
        }

        if (!is_null($this->getAmojoRights())) {
            $result['amojo_rights'] = $this->getAmojoRights();
        }

        if (!is_null($this->getAmoMessenger())) {
            $result['amo_messenger'] = $this->getAmoMessenger();
        }

        if ($this->getUsersGroups() && !$this->getUsersGroups()->isEmpty()) {
            $result['users_groups'] = $this->getUsersGroups();
        }

        if ($this->getTaskTypes() && !$this->getTaskTypes()->isEmpty()) {
            $result['task_types'] = $this->getTaskTypes();
        }

        if (!is_null($this->getVersion())) {
            $result['version'] = $this->getVersion();
        }

        if (!is_null($this->getDatetimeSettings())) {
            $result['datetime_settings'] = $this->getDatetimeSettings();
        }

        if (!is_null($this->getTotal())) {
            $result['total'] = $this->getTotal()->toArray();
        }

        if ($this->getBots() && !$this->getBots()->isEmpty()) {
            $result['bots'] = $this->getBots();
        }

        return $result;
    }

    /**
     * @return AmojoUrl|null
     */
    public function getAmojoUrl(): ?AmojoUrl
    {
        return $this->amojoUrl;
    }

    /**
     * @param AmojoUrl $amojoUrl
     * @return $this
     */
    public function setAmojoUrl(AmojoUrl $amojoUrl): self
    {
        $this->amojoUrl = $amojoUrl;

        return $this;
    }

    /**
     * @return AmojoRights|null
     */
    public function getAmojoRights(): ?AmojoRights
    {
        return $this->amojoRights;
    }

    /**
     * @param AmojoRights $amojoRights
     * @return $this
     */
    public function setAmojoRights(AmojoRights $amojoRights): self
    {
        $this->amojoRights = $amojoRights;

        return $this;
    }

    /**
     * @return AmoMessenger|null
     */
    public function getAmoMessenger(): ?AmoMessenger
    {
        return $this->amoMessenger;
    }

    /**
     * @param AmoMessenger $amoMessenger
     * @return AccountModel
     */
    public function setAmoMessenger(AmoMessenger $amoMessenger): self
    {
        $this->amoMessenger = $amoMessenger;

        return $this;
    }

    /**
     * @return UsersGroupsCollection
     */
    public function getUsersGroups(): UsersGroupsCollection
    {
        return $this->usersGroups;
    }

    /**
     * @param UsersGroupsCollection $usersGroups
     * @return $this
     */
    public function setUsersGroups(UsersGroupsCollection $usersGroups): self
    {
        $this->usersGroups = $usersGroups;

        return $this;
    }

    /**
     * @return TaskTypesCollection
     */
    public function getTaskTypes(): TaskTypesCollection
    {
        return $this->taskTypes;
    }

    /**
     * @param TaskTypesCollection $taskTypes
     * @return AccountModel
     */
    public function setTaskTypes(TaskTypesCollection $taskTypes): self
    {
        $this->taskTypes = $taskTypes;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getVersion(): ?int
    {
        return $this->version;
    }

    /**
     * @param int $version
     * @return AccountModel
     */
    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUnsortedOn(): bool
    {
        return $this->unsortedOn;
    }

    /**
     * @param bool $unsortedOn
     * @return AccountModel
     */
    public function setUnsortedOn(bool $unsortedOn): self
    {
        $this->unsortedOn = $unsortedOn;

        return $this;
    }

    /**
     * @return int
     */
    public function getMobileFeatureVersion(): int
    {
        return $this->mobileFeatureVersion;
    }

    /**
     * @param int $mobileFeatureVersion
     * @return AccountModel
     */
    public function setMobileFeatureVersion(int $mobileFeatureVersion): self
    {
        $this->mobileFeatureVersion = $mobileFeatureVersion;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCustomersEnabled(): bool
    {
        return $this->customersEnabled;
    }

    /**
     * @param bool $customersEnabled
     * @return AccountModel
     */
    public function setCustomersEnabled(bool $customersEnabled): self
    {
        $this->customersEnabled = $customersEnabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPeriodicityEnabled(): bool
    {
        return $this->periodicityEnabled;
    }

    /**
     * @param bool $periodicityEnabled
     * @return AccountModel
     */
    public function setPeriodicityEnabled(bool $periodicityEnabled): self
    {
        $this->periodicityEnabled = $periodicityEnabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLossReasonsEnabled(): bool
    {
        return $this->lossReasonsEnabled;
    }

    /**
     * @param bool $lossReasonsEnabled
     * @return AccountModel
     */
    public function setLossReasonsEnabled(bool $lossReasonsEnabled): self
    {
        $this->lossReasonsEnabled = $lossReasonsEnabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHelpbotEnabled(): bool
    {
        return $this->helpbotEnabled;
    }

    /**
     * @param bool $helpbotEnabled
     * @return AccountModel
     */
    public function setHelpbotEnabled(bool $helpbotEnabled): self
    {
        $this->helpbotEnabled = $helpbotEnabled;

        return $this;
    }

    /**
     * @return int
     */
    public function getContactNameDisplayOrder(): int
    {
        return $this->contactNameDisplayOrder;
    }

    /**
     * @param int $contactNameDisplayOrder
     * @return AccountModel
     */
    public function setContactNameDisplayOrder(int $contactNameDisplayOrder): self
    {
        $this->contactNameDisplayOrder = $contactNameDisplayOrder;

        return $this;
    }

    /**
     * @return DateTimeSettings|null
     */
    public function getDatetimeSettings(): ?DateTimeSettings
    {
        return $this->datetimeSettings;
    }

    /**
     * @param DateTimeSettings $datetimeSettings
     * @return AccountModel
     */
    public function setDatetimeSettings(DateTimeSettings $datetimeSettings): self
    {
        $this->datetimeSettings = $datetimeSettings;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentUserId()
    {
        return $this->currentUserId;
    }

    /**
     * @param int $currentUserId
     * @return AccountModel
     */
    public function setCurrentUserId(int $currentUserId): self
    {
        $this->currentUserId = $currentUserId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     * @return AccountModel
     */
    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Total|null
     */
    public function getTotal(): ?Total
    {
        return $this->total;
    }

    /**
     * @param Total $total
     * @return AccountModel
     */
    public function setTotal(Total $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return BotsCollection
     */
    public function getBots(): BotsCollection
    {
        return $this->bots;
    }

    /**
     * @param BotsCollection $bots
     * @return AccountModel
     */
    public function setBots(BotsCollection $bots): self
    {
        $this->bots = $bots;

        return $this;
    }

    /**
     * @return array
     */
    public static function getAvailableWith(): array
    {
        return [
            self::AMOJO_ID,
            self::UUID,
            self::NOTIFICATIONS_INFO,
            self::AMOJO_URL,
            self::AMOJO_RIGHTS,
            self::AMO_MESSENGER,
            self::USER_GROUPS,
            self::BOTS,
            self::TASK_TYPES,
            self::TOTAL,
            self::VERSION,
            self::DATETIME_SETTINGS,
        ];
    }

    /**
     * @param int|null $requestId
     * @return array
     */
    public function toApi(int $requestId = null): array
    {
        return $this->toArray();
    }
}
