<?php

namespace AmoCRM\Analytics\Models;

use AmoCRM\Analytics\BaseAnalyticsModel;
use AmoCRM\Models\Unsorted\BaseUnsortedModel;

/**
 * Class FirstTouchFactModel
 *
 * Модель для хранения данных о первых касаниях (неразобранное).
 * Используется для аналитики источников привлечения и first-touch атрибуции.
 *
 * @package AmoCRM\Analytics\Models
 */
class FirstTouchFactModel extends BaseAnalyticsModel
{
    /**
     * UID неразобранного
     * @var string|null
     */
    protected $unsortedUid;

    /**
     * Категория (sip, mail, forms, chats)
     * @var string|null
     */
    protected $category;

    /**
     * Название источника
     * @var string|null
     */
    protected $sourceName;

    /**
     * UID источника в соцсети/мессенджере
     * @var string|null
     */
    protected $sourceUid;

    /**
     * ID воронки для создания сделки
     * @var int|null
     */
    protected $pipelineId;

    /**
     * ID созданной сделки (после принятия)
     * @var int|null
     */
    protected $leadId;

    /**
     * ID контакта из обращения
     * @var int|null
     */
    protected $contactId;

    /**
     * Флаг принятия обращения
     * @var bool
     */
    protected $isAccepted = false;

    /**
     * Флаг отклонения обращения
     * @var bool
     */
    protected $isDeclined = false;

    /**
     * Метаданные обращения (JSON)
     * @var array|null
     */
    protected $metadata;

    /**
     * Имя клиента (если есть)
     * @var string|null
     */
    protected $clientName;

    /**
     * Телефон клиента (если есть)
     * @var string|null
     */
    protected $clientPhone;

    /**
     * Email клиента (если есть)
     * @var string|null
     */
    protected $clientEmail;

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->unsortedUid;
    }

    /**
     * @param string|null $id
     * @return self
     */
    public function setId($id): self
    {
        $this->unsortedUid = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUnsortedUid(): ?string
    {
        return $this->unsortedUid;
    }

    /**
     * @param string|null $unsortedUid
     * @return self
     */
    public function setUnsortedUid(?string $unsortedUid): self
    {
        $this->unsortedUid = $unsortedUid;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     * @return self
     */
    public function setCategory(?string $category): self
    {
        $this->category = $category;
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
     * @return self
     */
    public function setSourceName(?string $sourceName): self
    {
        $this->sourceName = $sourceName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSourceUid(): ?string
    {
        return $this->sourceUid;
    }

    /**
     * @param string|null $sourceUid
     * @return self
     */
    public function setSourceUid(?string $sourceUid): self
    {
        $this->sourceUid = $sourceUid;
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
     * @return self
     */
    public function setPipelineId(?int $pipelineId): self
    {
        $this->pipelineId = $pipelineId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLeadId(): ?int
    {
        return $this->leadId;
    }

    /**
     * @param int|null $leadId
     * @return self
     */
    public function setLeadId(?int $leadId): self
    {
        $this->leadId = $leadId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getContactId(): ?int
    {
        return $this->contactId;
    }

    /**
     * @param int|null $contactId
     * @return self
     */
    public function setContactId(?int $contactId): self
    {
        $this->contactId = $contactId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAccepted(): bool
    {
        return $this->isAccepted;
    }

    /**
     * @param bool $isAccepted
     * @return self
     */
    public function setIsAccepted(bool $isAccepted): self
    {
        $this->isAccepted = $isAccepted;
        if ($isAccepted) {
            $this->isDeclined = false;
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isDeclined(): bool
    {
        return $this->isDeclined;
    }

    /**
     * @param bool $isDeclined
     * @return self
     */
    public function setIsDeclined(bool $isDeclined): self
    {
        $this->isDeclined = $isDeclined;
        if ($isDeclined) {
            $this->isAccepted = false;
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    /**
     * @param array|null $metadata
     * @return self
     */
    public function setMetadata(?array $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    /**
     * @param string|null $clientName
     * @return self
     */
    public function setClientName(?string $clientName): self
    {
        $this->clientName = $clientName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getClientPhone(): ?string
    {
        return $this->clientPhone;
    }

    /**
     * @param string|null $clientPhone
     * @return self
     */
    public function setClientPhone(?string $clientPhone): self
    {
        $this->clientPhone = $clientPhone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getClientEmail(): ?string
    {
        return $this->clientEmail;
    }

    /**
     * @param string|null $clientEmail
     * @return self
     */
    public function setClientEmail(?string $clientEmail): self
    {
        $this->clientEmail = $clientEmail;
        return $this;
    }

    /**
     * Проверить, является ли обращение "горячим" (sip или чат)
     *
     * @return bool
     */
    public function isHotLead(): bool
    {
        return in_array($this->category, [
            BaseUnsortedModel::CATEGORY_CODE_SIP,
            BaseUnsortedModel::CATEGORY_CODE_CHATS,
        ], true);
    }

    /**
     * Проверить, является ли обращение из формы
     *
     * @return bool
     */
    public function isFromForm(): bool
    {
        return $this->category === BaseUnsortedModel::CATEGORY_CODE_FORMS;
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $data): self
    {
        $model = new self();

        $model->setUnsortedUid($data['unsorted_uid'] ?? $data['uid'] ?? null);
        $model->setCategory($data['category'] ?? null);
        $model->setSourceName($data['source_name'] ?? null);
        $model->setSourceUid($data['source_uid'] ?? null);
        $model->setPipelineId($data['pipeline_id'] ?? null);
        $model->setLeadId($data['lead_id'] ?? null);
        $model->setContactId($data['contact_id'] ?? null);
        $model->setIsAccepted($data['is_accepted'] ?? false);
        $model->setIsDeclined($data['is_declined'] ?? false);
        $model->setMetadata($data['metadata'] ?? null);
        $model->setClientName($data['client_name'] ?? null);
        $model->setClientPhone($data['client_phone'] ?? null);
        $model->setClientEmail($data['client_email'] ?? null);
        $model->setCreatedAt($data['created_at'] ?? null);
        $model->setUpdatedAt($data['updated_at'] ?? null);
        $model->setAccountId($data['account_id'] ?? null);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'unsorted_uid' => $this->unsortedUid,
            'category' => $this->category,
            'source_name' => $this->sourceName,
            'source_uid' => $this->sourceUid,
            'pipeline_id' => $this->pipelineId,
            'lead_id' => $this->leadId,
            'contact_id' => $this->contactId,
            'is_accepted' => $this->isAccepted,
            'is_declined' => $this->isDeclined,
            'metadata' => $this->metadata,
            'client_name' => $this->clientName,
            'client_phone' => $this->clientPhone,
            'client_email' => $this->clientEmail,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'account_id' => $this->accountId,
        ];
    }

    /**
     * Создать FirstTouchFactModel из API Unsorted модели
     *
     * @param BaseUnsortedModel $unsortedModel
     * @return self
     */
    public static function fromApiModel(BaseUnsortedModel $unsortedModel): self
    {
        $model = new self();

        $model->setUnsortedUid($unsortedModel->getUid())
            ->setCategory($unsortedModel->getCategory())
            ->setSourceName($unsortedModel->getSourceName())
            ->setSourceUid($unsortedModel->getSourceUid())
            ->setPipelineId($unsortedModel->getPipelineId())
            ->setCreatedAt($unsortedModel->getCreatedAt());

        // Извлекаем информацию о контакте
        $contacts = $unsortedModel->getContacts();
        if ($contacts !== null && !$contacts->isEmpty()) {
            $contact = $contacts->first();
            if ($contact !== null) {
                $model->setContactId($contact->getId());
                $model->setClientName($contact->getName());
            }
        }

        // Извлекаем информацию о созданной сделке
        $lead = $unsortedModel->getLead();
        if ($lead !== null) {
            $model->setLeadId($lead->getId());
        }

        // Извлекаем метаданные
        $metadata = $unsortedModel->getMetadata();
        if ($metadata !== null) {
            $model->setMetadata($metadata->toArray() ?? []);
        }

        return $model;
    }

    /**
     * Создать модель из данных вебхука
     *
     * @param array $webhookData Данные из вебхука
     * @return self
     */
    public static function fromWebhook(array $webhookData): self
    {
        $model = new self();

        $model->setUnsortedUid($webhookData['uid'] ?? null)
            ->setCategory($webhookData['category'] ?? null)
            ->setSourceName($webhookData['source_name'] ?? null)
            ->setSourceUid($webhookData['source_uid'] ?? null)
            ->setPipelineId($webhookData['pipeline_id'] ?? null)
            ->setCreatedAt($webhookData['created_at'] ?? null);

        // Проверяем, было ли обращение принято/отклонено
        if (isset($webhookData['action'])) {
            $model->setIsAccepted($webhookData['action'] === 'accept');
            $model->setIsDeclined($webhookData['action'] === 'decline');
        }

        // Извлекаем информацию о сделке из embedded данных
        if (isset($webhookData['_embedded']['leads'][0])) {
            $model->setLeadId($webhookData['_embedded']['leads'][0]['id'] ?? null);
        }

        // Извлекаем информацию о контакте
        if (isset($webhookData['_embedded']['contacts'][0])) {
            $model->setContactId($webhookData['_embedded']['contacts'][0]['id'] ?? null);
        }

        return $model;
    }
}