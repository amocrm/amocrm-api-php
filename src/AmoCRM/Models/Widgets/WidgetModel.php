<?php

namespace AmoCRM\Models\Widgets;

use AmoCRM\Collections\Widgets\SettingsTemplatesCollection;
use AmoCRM\Models\BaseApiModel;
use InvalidArgumentException;

/**
 * Class WidgetModel
 *
 * @package AmoCRM\Models
 */
class WidgetModel extends BaseApiModel
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string|int|null
     */
    protected $version;

    /**
     * @var string|null
     */
    protected $rating;

    /**
     * @var SettingsTemplatesCollection|null
     */
    protected $settingsTemplate;

    /**
     * @var bool|null
     */
    protected $isLeadSource;

    /**
     * @var bool|null
     */
    protected $isActiveInAccount;

    /**
     * @var bool|null
     */
    protected $isWorkWithDp;

    /**
     * @var bool|null
     */
    protected $isCrmTemplate;

    /**
     * @var string|null
     */
    protected $clientUuid;

    /**
     * @var int|null
     */
    protected $pipelineId;

    /**
     * Данные свойство доступно только в случае установки виджета
     * и в случае запроса от связанного с виджетом oAuth клиента
     * @var array|null
     */
    protected $settings;

    /**
     * @param array $widget
     *
     * @return self
     */
    public static function fromArray(array $widget): self
    {
        if (empty($widget['id'])) {
            throw new InvalidArgumentException('Widget id is empty in ' . json_encode($widget));
        }

        $model = new self();

        $model
            ->setId($widget['id'])
            ->setCode($widget['code'])
            ->setVersion($widget['version'])
            ->setRating($widget['rating'])
            ->setSettingsTemplate(SettingsTemplatesCollection::fromArray($widget['settings_template']))
            ->setIsLeadSource($widget['is_lead_source'])
            ->setIsWorkWithDp($widget['is_work_with_dp'])
            ->setIsCrmTemplate($widget['is_crm_template'])
            ->setIsActiveInAccount($widget['is_active_in_account'])
            ->setPipelineId($widget['pipeline_id'])
            ->setClientUuid($widget['client_uuid'])
            ->setSettings($widget['settings'] ?? null);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'code' => $this->getCode(),
            'version' => $this->getVersion(),
            'rating' => $this->getRating(),
            'settings_template' => $this->getSettingsTemplate(),
            'is_lead_source' => $this->getIsLeadSource(),
            'is_work_with_dp' => $this->getIsWorkWithDp(),
            'is_crm_template' => $this->getIsCrmTemplate(),
            'is_active_in_account' => $this->getIsActiveInAccount(),
            'pipeline_id' => $this->getPipelineId(),
            'client_uuid' => $this->getClientUuid(),
            'settings' => $this->getSettings(),
        ];
    }

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
     * @return WidgetModel
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return WidgetModel
     */
    public function setCode(string $code): WidgetModel
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int|string|null $version
     *
     * @return WidgetModel
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRating(): ?string
    {
        return $this->rating;
    }

    /**
     * @param string|null $rating
     *
     * @return WidgetModel
     */
    public function setRating(?string $rating): WidgetModel
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return SettingsTemplatesCollection
     */
    public function getSettingsTemplate(): ?SettingsTemplatesCollection
    {
        return $this->settingsTemplate;
    }

    /**
     * @param null|SettingsTemplatesCollection $settingsTemplate
     *
     * @return WidgetModel
     */
    public function setSettingsTemplate(?SettingsTemplatesCollection $settingsTemplate): WidgetModel
    {
        $this->settingsTemplate = $settingsTemplate;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsLeadSource(): ?bool
    {
        return $this->isLeadSource;
    }

    /**
     * @param bool|null $isLeadSource
     *
     * @return WidgetModel
     */
    public function setIsLeadSource(?bool $isLeadSource): WidgetModel
    {
        $this->isLeadSource = $isLeadSource;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsActiveInAccount(): ?bool
    {
        return $this->isActiveInAccount;
    }

    /**
     * @param bool|null $isActiveInAccount
     *
     * @return WidgetModel
     */
    public function setIsActiveInAccount(?bool $isActiveInAccount): WidgetModel
    {
        $this->isActiveInAccount = $isActiveInAccount;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsWorkWithDp(): ?bool
    {
        return $this->isWorkWithDp;
    }

    /**
     * @param bool|null $isWorkWithDp
     *
     * @return WidgetModel
     */
    public function setIsWorkWithDp(?bool $isWorkWithDp): WidgetModel
    {
        $this->isWorkWithDp = $isWorkWithDp;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsCrmTemplate(): ?bool
    {
        return $this->isCrmTemplate;
    }

    /**
     * @param bool|null $isCrmTemplate
     *
     * @return WidgetModel
     */
    public function setIsCrmTemplate(?bool $isCrmTemplate): WidgetModel
    {
        $this->isCrmTemplate = $isCrmTemplate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getClientUuid(): ?string
    {
        return $this->clientUuid;
    }

    /**
     * @param string|null $clientUuid
     *
     * @return WidgetModel
     */
    public function setClientUuid(?string $clientUuid): WidgetModel
    {
        $this->clientUuid = $clientUuid;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPipelineId(): ?int
    {
        return $this->pipelineId;
    }

    /**
     * @param int|null $pipelineId
     *
     * @return WidgetModel
     */
    public function setPipelineId(?int $pipelineId): WidgetModel
    {
        $this->pipelineId = $pipelineId;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getSettings(): ?array
    {
        return $this->settings;
    }

    /**
     * @param array|null $settings
     *
     * @return WidgetModel
     */
    public function setSettings(?array $settings): WidgetModel
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        return $this->getSettings();
    }
}
