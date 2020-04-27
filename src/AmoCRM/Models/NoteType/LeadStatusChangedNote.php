<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\NoteModel;

class LeadStatusChangedNote extends NoteModel
{
    protected $modelClass = LeadStatusChangedNote::class;

    /**
     * @var null|int
     */
    protected $oldStatusId;

    /**
     * @var null|int
     */
    protected $oldPipelineId;

    /**
     * @var null|int
     */
    protected $newStatusId;

    /**
     * @var null|int
     */
    protected $newPipelineId;

    /**
     * @var null|int
     */
    protected $lossReasonId;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_LEAD_STATUS_CHANGED;
    }

    /**
     * @param array $note
     *
     * @return self
     */
    public function fromArray(array $note): NoteModel
    {
        $model = parent::fromArray($note);

        if (isset($note['params']['new_status_id'])) {
            $this->setNewStatusId($note['params']['new_status_id']);
        }

        if (isset($note['params']['old_status_id'])) {
            $this->setOldStatusId($note['params']['old_status_id']);
        }

        if (isset($note['params']['new_pipeline_id'])) {
            $this->setNewPipelineId($note['params']['new_pipeline_id']);
        }

        if (isset($note['params']['old_pipeline_id'])) {
            $this->setOldPipelineId($note['params']['old_pipeline_id']);
        }

        if (isset($note['params']['loss_reason_id'])) {
            $this->setLossReasonId($note['params']['loss_reason_id']);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['params'] = [
            'new_status_id' => $this->getNewStatusId(),
            'old_status_id' => $this->getOldStatusId(),
            'new_pipeline_id' => $this->getNewPipelineId(),
            'old_pipeline_id' => $this->getOldPipelineId(),
            'loss_reason_id' => $this->getLossReasonId(),
        ];

        return $result;
    }

    /**
     * @param int|null $requestId
     * @return array
     * @throws NotAvailableForActionException
     */
    public function toApi(int $requestId = null): array
    {
        throw new NotAvailableForActionException();
    }

    /**
     * @return int|null
     */
    public function getOldStatusId(): ?int
    {
        return $this->oldStatusId;
    }

    /**
     * @param int|null $oldStatusId
     * @return LeadStatusChangedNote
     */
    public function setOldStatusId(?int $oldStatusId): LeadStatusChangedNote
    {
        $this->oldStatusId = $oldStatusId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getOldPipelineId(): ?int
    {
        return $this->oldPipelineId;
    }

    /**
     * @param int|null $oldPipelineId
     * @return LeadStatusChangedNote
     */
    public function setOldPipelineId(?int $oldPipelineId): LeadStatusChangedNote
    {
        $this->oldPipelineId = $oldPipelineId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNewStatusId(): ?int
    {
        return $this->newStatusId;
    }

    /**
     * @param int|null $newStatusId
     * @return LeadStatusChangedNote
     */
    public function setNewStatusId(?int $newStatusId): LeadStatusChangedNote
    {
        $this->newStatusId = $newStatusId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNewPipelineId(): ?int
    {
        return $this->newPipelineId;
    }

    /**
     * @param int|null $newPipelineId
     * @return LeadStatusChangedNote
     */
    public function setNewPipelineId(?int $newPipelineId): LeadStatusChangedNote
    {
        $this->newPipelineId = $newPipelineId;

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
     * @return LeadStatusChangedNote
     */
    public function setLossReasonId(?int $lossReasonId): LeadStatusChangedNote
    {
        $this->lossReasonId = $lossReasonId;

        return $this;
    }
}
