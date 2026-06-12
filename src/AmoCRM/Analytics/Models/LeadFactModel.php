<?php

namespace AmoCRM\Analytics\Models;

use AmoCRM\Analytics\BaseAnalyticsModel;
use AmoCRM\Models\LeadModel;

/**
 * Class LeadFactModel
 *
 * Модель аналитического факта для сделок.
 * Хранит денормализованные данные о сделках для аналитики.
 *
 * @package AmoCRM\Analytics\Models
 */
class LeadFactModel extends BaseAnalyticsModel
{
    /**
     * ID сделки
     * @var int|null
     */
    protected $leadId;

    /**
     * Название сделки
     * @var string|null
     */
    protected $name;

    /**
     * ID воронки
     * @var int|null
     */
    protected $pipelineId;

    /**
     * ID статуса (этапа воронки)
     * @var int|null
     */
    protected $statusId;

    /**
     * Бюджет сделки
     * @var float|null
     */
    protected $price;

    /**
     * Дата закрытия сделки
     * @var int|null
     */
    protected $closedAt;

    /**
     * ID источника привлечения
     * @var int|null
     */
    protected $sourceId;

    /**
     * Название источника
     * @var string|null
     */
    protected $sourceName;

    /**
     * Внешний ID источника
     * @var string|null
     */
    protected $sourceExternalId;

    /**
     * ID ответственного пользователя
     * @var int|null
     */
    protected $responsibleUserId;

    /**
     * ID основного контакта
     * @var int|null
     */
    protected $contactId;

    /**
     * ID компании
     * @var int|null
     */
    protected $companyId;

    /**
     * ID причины отказа
     * @var int|null
     */
    protected $lossReasonId;

    /**
     * Оценка сделки (баллы)
     * @var int|null
     */
    protected $score;

    /**
     * Флаг удаления
     * @var bool
     */
    protected $isDeleted = false;

    /**
     * Флаг победы
     * @var bool
     */
    protected $isWon = false;

    /**
     * Флаг проигрыша
     * @var bool
     */
    protected $isLost = false;

    /**
     * UID посетителя сайта
     * @var string|null
     */
    protected $visitorUid;

    /**
     * ID группы
     * @var int|null
     */
    protected $groupId;

    /**
     * ID создателя
     * @var int|null
     */
    protected $createdBy;

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->leadId;
    }

    /**
     * @param int|null $id
     * @return self
     */
    public function setId($id): self
    {
        $this->leadId = $id;
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
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
    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    /**
     * @param int|null $statusId
     * @return self
     */
    public function setStatusId(?int $statusId): self
    {
        $this->statusId = $statusId;
        $this->updateWinLossFlags();
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     * @return self
     */
    public function setPrice($price): self
    {
        $this->price = $price !== null ? (float)$price : null;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getClosedAt(): ?int
    {
        return $this->closedAt;
    }

    /**
     * @param int|null $closedAt
     * @return self
     */
    public function setClosedAt(?int $closedAt): self
    {
        $this->closedAt = $closedAt;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSourceId(): ?int
    {
        return $this->sourceId;
    }

    /**
     * @param int|null $sourceId
     * @return self
     */
    public function setSourceId(?int $sourceId): self
    {
        $this->sourceId = $sourceId;
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
    public function getSourceExternalId(): ?string
    {
        return $this->sourceExternalId;
    }

    /**
     * @param string|null $sourceExternalId
     * @return self
     */
    public function setSourceExternalId(?string $sourceExternalId): self
    {
        $this->sourceExternalId = $sourceExternalId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getResponsibleUserId(): ?int
    {
        return $this->responsibleUserId;
    }

    /**
     * @param int|null $responsibleUserId
     * @return self
     */
    public function setResponsibleUserId(?int $responsibleUserId): self
    {
        $this->responsibleUserId = $responsibleUserId;
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
     * @return int|null
     */
    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    /**
     * @param int|null $companyId
     * @return self
     */
    public function setCompanyId(?int $companyId): self
    {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLossReasonId(): ?int
    {
        return $this->lossReasonId;
    }

    /**
     * @param int|null $lossReasonId
     * @return self
     */
    public function setLossReasonId(?int $lossReasonId): self
    {
        $this->lossReasonId = $lossReasonId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getScore(): ?int
    {
        return $this->score;
    }

    /**
     * @param int|null $score
     * @return self
     */
    public function setScore(?int $score): self
    {
        $this->score = $score;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     * @return self
     */
    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWon(): bool
    {
        return $this->isWon;
    }

    /**
     * @param bool $isWon
     * @return self
     */
    public function setIsWon(bool $isWon): self
    {
        $this->isWon = $isWon;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLost(): bool
    {
        return $this->isLost;
    }

    /**
     * @param bool $isLost
     * @return self
     */
    public function setIsLost(bool $isLost): self
    {
        $this->isLost = $isLost;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVisitorUid(): ?string
    {
        return $this->visitorUid;
    }

    /**
     * @param string|null $visitorUid
     * @return self
     */
    public function setVisitorUid(?string $visitorUid): self
    {
        $this->visitorUid = $visitorUid;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getGroupId(): ?int
    {
        return $this->groupId;
    }

    /**
     * @param int|null $groupId
     * @return self
     */
    public function setGroupId(?int $groupId): self
    {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    /**
     * @param int|null $createdBy
     * @return self
     */
    public function setCreatedBy(?int $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * Обновить флаги isWon/isLost на основе statusId
     */
    protected function updateWinLossFlags(): void
    {
        if ($this->statusId !== null) {
            $this->isWon = $this->statusId === LeadModel::WON_STATUS_ID;
            $this->isLost = $this->statusId === LeadModel::LOST_STATUS_ID;
        }
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $data): self
    {
        $model = new self();

        $model->setLeadId($data['lead_id'] ?? $data['id'] ?? null);
        $model->setName($data['name'] ?? null);
        $model->setPipelineId($data['pipeline_id'] ?? null);
        $model->setStatusId($data['status_id'] ?? null);
        $model->setPrice($data['price'] ?? null);
        $model->setCreatedAt($data['created_at'] ?? null);
        $model->setUpdatedAt($data['updated_at'] ?? null);
        $model->setClosedAt($data['closed_at'] ?? null);
        $model->setSourceId($data['source_id'] ?? null);
        $model->setSourceName($data['source_name'] ?? null);
        $model->setSourceExternalId($data['source_external_id'] ?? null);
        $model->setResponsibleUserId($data['responsible_user_id'] ?? null);
        $model->setContactId($data['contact_id'] ?? null);
        $model->setCompanyId($data['company_id'] ?? null);
        $model->setLossReasonId($data['loss_reason_id'] ?? null);
        $model->setScore($data['score'] ?? null);
        $model->setIsDeleted($data['is_deleted'] ?? false);
        $model->setIsWon($data['is_won'] ?? false);
        $model->setIsLost($data['is_lost'] ?? false);
        $model->setVisitorUid($data['visitor_uid'] ?? null);
        $model->setGroupId($data['group_id'] ?? null);
        $model->setCreatedBy($data['created_by'] ?? null);
        $model->setAccountId($data['account_id'] ?? null);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'lead_id' => $this->leadId,
            'name' => $this->name,
            'pipeline_id' => $this->pipelineId,
            'status_id' => $this->statusId,
            'price' => $this->price,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'closed_at' => $this->closedAt,
            'source_id' => $this->sourceId,
            'source_name' => $this->sourceName,
            'source_external_id' => $this->sourceExternalId,
            'responsible_user_id' => $this->responsibleUserId,
            'contact_id' => $this->contactId,
            'company_id' => $this->companyId,
            'loss_reason_id' => $this->lossReasonId,
            'score' => $this->score,
            'is_deleted' => $this->isDeleted,
            'is_won' => $this->isWon,
            'is_lost' => $this->isLost,
            'visitor_uid' => $this->visitorUid,
            'group_id' => $this->groupId,
            'created_by' => $this->createdBy,
            'account_id' => $this->accountId,
        ];
    }

    /**
     * Создать LeadFactModel из API LeadModel
     *
     * @param LeadModel $leadModel
     * @return self
     */
    public static function fromApiModel(LeadModel $leadModel): self
    {
        $model = new self();

        $model->setLeadId($leadModel->getId())
            ->setName($leadModel->getName())
            ->setPipelineId($leadModel->getPipelineId())
            ->setStatusId($leadModel->getStatusId())
            ->setPrice($leadModel->getPrice())
            ->setCreatedAt($leadModel->getCreatedAt())
            ->setUpdatedAt($leadModel->getUpdatedAt())
            ->setClosedAt($leadModel->getClosedAt())
            ->setSourceId($leadModel->getSourceId())
            ->setResponsibleUserId($leadModel->getResponsibleUserId())
            ->setLossReasonId($leadModel->getLossReasonId())
            ->setScore($leadModel->getScore())
            ->setIsDeleted($leadModel->getIsDeleted())
            ->setVisitorUid($leadModel->getVisitorUid())
            ->setGroupId($leadModel->getGroupId())
            ->setCreatedBy($leadModel->getCreatedBy());

        // Извлекаем информацию об источнике
        if ($leadModel->getSource() !== null) {
            $model->setSourceName($leadModel->getSource()->getName());
            $model->setSourceExternalId($leadModel->getSource()->getExternalId());
        }

        // Извлекаем основной контакт
        $contacts = $leadModel->getContacts();
        if ($contacts !== null && !$contacts->isEmpty()) {
            $mainContact = $contacts->getBy('isMain', true) ?? $contacts->first();
            if ($mainContact !== null) {
                $model->setContactId($mainContact->getId());
            }
        }

        // Извлекаем компанию
        $company = $leadModel->getCompany();
        if ($company !== null) {
            $model->setCompanyId($company->getId());
        }

        // Обновляем флаги isWon/isLost
        $model->updateWinLossFlags();

        return $model;
    }
}