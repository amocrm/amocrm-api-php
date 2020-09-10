<?php

namespace AmoCRM\Models\Unsorted;

use AmoCRM\Models\Unsorted\Interfaces\UnsortedMetadataInterface;
use AmoCRM\Models\BaseApiModel;
use Illuminate\Contracts\Support\Arrayable;

class FormsMetadata extends BaseApiModel implements Arrayable, UnsortedMetadataInterface
{
    /**
     * @var string|int|null
     */
    protected $formId;

    /**
     * @var string|null
     */
    protected $formName;

    /**
     * @var string|null
     */
    protected $formPage;

    /**
     * @var string|null
     */
    protected $ip;

    /**
     * @var int|null
     */
    protected $formSentAt;

    /**
     * @var string|null
     */
    protected $referer;

    /**
     * @var string|null
     */
    protected $visitorUid;

    /**
     * @param array $metadata
     *
     * @return self
     */
    public static function fromArray(array $metadata): self
    {
        $model = new self();

        $model->setFormId($metadata['form_id']);
        $model->setFormName($metadata['form_name']);
        $model->setFormPage($metadata['form_page']);
        $model->setIp($metadata['ip']);
        $model->setFormSentAt((int)$metadata['form_sent_at']);
        $model->setReferer($metadata['referer']);
        $model->setVisitorUid($metadata['visitor_uid']);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'form_id' => $this->getFormId(),
            'form_name' => $this->getFormName(),
            'form_page' => $this->getFormPage(),
            'ip' => $this->getIp(),
            'form_sent_at' => $this->getFormSentAt(),
            'referer' => $this->getReferer(),
            'visitor_uid' => $this->getVisitorUid(),
        ];
    }

    /**
     * @return int|string|null
     */
    public function getFormId()
    {
        return $this->formId;
    }

    /**
     * @param int|string|null $formId
     * @return FormsMetadata
     */
    public function setFormId($formId)
    {
        $this->formId = $formId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormName(): ?string
    {
        return $this->formName;
    }

    /**
     * @param string|null $formName
     * @return FormsMetadata
     */
    public function setFormName(?string $formName): FormsMetadata
    {
        $this->formName = $formName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormPage(): ?string
    {
        return $this->formPage;
    }

    /**
     * @param string|null $formPage
     * @return FormsMetadata
     */
    public function setFormPage(?string $formPage): FormsMetadata
    {
        $this->formPage = $formPage;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string|null $ip
     * @return FormsMetadata
     */
    public function setIp(?string $ip): FormsMetadata
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFormSentAt(): ?int
    {
        return $this->formSentAt;
    }

    /**
     * @param int|null $formSentAt
     * @return FormsMetadata
     */
    public function setFormSentAt(?int $formSentAt): FormsMetadata
    {
        $this->formSentAt = $formSentAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReferer(): ?string
    {
        return $this->referer;
    }

    /**
     * @param string|null $referer
     * @return FormsMetadata
     */
    public function setReferer(?string $referer): FormsMetadata
    {
        $this->referer = $referer;

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
     * @return FormsMetadata
     */
    public function setVisitorUid(?string $visitorUid): FormsMetadata
    {
        $this->visitorUid = $visitorUid;

        return $this;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        return [
            'form_id' => $this->getFormId(),
            'form_name' => $this->getFormName(),
            'form_page' => $this->getFormPage(),
            'ip' => $this->getIp(),
            'form_sent_at' => $this->getFormSentAt() ?? time(),
            'referer' => $this->getReferer(),
            'visitor_uid' => $this->getVisitorUid(),
        ];
    }
}
