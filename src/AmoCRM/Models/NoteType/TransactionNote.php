<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\NoteModel;

class TransactionNote extends NoteModel
{
    protected $modelClass = TransactionNote::class;

    /**
     * @var null|int
     */
    protected $transactionId;

    public function getNoteType(): string
    {
        return NoteFactory::NOTE_TYPE_CODE_TRANSACTION;
    }

    /**
     * @param array $note
     *
     * @return self
     */
    public function fromArray(array $note): NoteModel
    {
        $model = parent::fromArray($note);

        if (isset($note['params']['transaction_id'])) {
            $model->setTransactionId($note['params']['transaction_id']);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = parent::toArray();

        $result['params']['transaction_id'] = $this->getTransactionId();

        return $result;
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

    /**
     * @return int|null
     */
    public function getTransactionId(): ?int
    {
        return $this->transactionId;
    }

    /**
     * @param int|null $transactionId
     * @return TransactionNote
     */
    public function setTransactionId(?int $transactionId): TransactionNote
    {
        $this->transactionId = $transactionId;

        return $this;
    }
}
