<?php

namespace AmoCRM\Models\NoteType;

use AmoCRM\Models\NoteModel;
use AmoCRM\Models\Traits\CallTrait;

use function is_string;

abstract class CallNote extends NoteModel
{
    use CallTrait;

    /**
     * @param array $note
     *
     * @return self
     */
    public function fromArray(array $note): NoteModel
    {
        /** @var CallNote $model */
        $model = parent::fromArray($note);

        if (isset($note['params']['uniq']) && is_string($note['params']['uniq'])) {
            $model->setUniq($note['params']['uniq']);
        }

        if (isset($note['params']['duration'])) {
            $model->setDuration($note['params']['duration']);
        }

        if (isset($note['params']['source'])) {
            $model->setSource($note['params']['source']);
        }

        if (isset($note['params']['link'])) {
            $model->setLink($note['params']['link']);
        }

        if (isset($note['params']['phone'])) {
            $model->setPhone($note['params']['phone']);
        }

        if (isset($note['params']['call_result'])) {
            $model->setCallResult($note['params']['call_result']);
        }

        if (isset($note['params']['call_status'])) {
            $model->setCallStatus($note['params']['call_status']);
        }

        if (isset($note['params']['call_responsible'])) {
            $model->setCallResponsible($note['params']['call_responsible']);
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
            'uniq' => $this->getUniq(),
            'duration' => $this->getDuration(),
            'source' => $this->getSource(),
            'link' => $this->getLink(),
            'phone' => $this->getPhone(),
            'call_result' => $this->getCallResult(),
            'call_status' => $this->getCallStatus(),
            'call_responsible' => $this->getCallResponsible(),
        ];

        return $result;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = parent::toApi($requestId);

        $result['params'] = [
            'uniq' => $this->getUniq(),
            'duration' => $this->getDuration(),
            'source' => $this->getSource(),
            'link' => $this->getLink(),
            'phone' => $this->getPhone(),
            'call_result' => $this->getCallResult(),
            'call_status' => $this->getCallStatus(),
            'call_responsible' => $this->getCallResponsible(),
        ];

        return $result;
    }
}
