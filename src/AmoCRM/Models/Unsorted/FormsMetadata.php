<?php

namespace AmoCRM\Models\Unsorted;

use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Unsorted\Interfaces\UnsortedMetadataInterface;
use AmoCRM\Contracts\Support\Arrayable;

use function array_key_exists;
use function is_null;
use function time;

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
     * @var int|null
     */
    protected $formType;

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
        $model->setFormSentAt($metadata['form_sent_at']);

        if (array_key_exists('ip', $metadata) && !is_null($metadata['ip'])) {
            $model->setIp($metadata['ip']);
        }
        if (array_key_exists('form_type', $metadata) && !is_null($metadata['form_type'])) {
            $model->setFormType((int)$metadata['form_type']);
        }
        if (array_key_exists('referer', $metadata) && !is_null($metadata['referer'])) {
            $model->setReferer($metadata['referer']);
        }
        if (array_key_exists('visitor_uid', $metadata) && !is_null($metadata['visitor_uid'])) {
            $model->setVisitorUid($metadata['visitor_uid']);
        }

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
            'form_type' => $this->getFormType(),
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

    public function setFormType(int $formType): self
    {
        $this->formType = $formType;

        return $this;
    }

    public function getFormType(): ?int
    {
        return $this->formType;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = [
            'form_id'      => $this->getFormId(),
            'form_name'    => $this->getFormName(),
            'form_page'    => $this->getFormPage(),
            'form_sent_at' => $this->getFormSentAt() ?? time(),
        ];

        if ($ip = $this->getIp()) {
            $result['ip'] = $ip;
        }
        if ($referer = $this->getReferer()) {
            $result['referer'] = $referer;
        }
        if ($formType = $this->getFormType()) {
            $result['form_type'] = $formType;
        }
        if ($visitorUid = $this->getVisitorUid()) {
            $result['visitor_uid'] = $visitorUid;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function toComplexApi(): array
    {
        $result = $this->toApi();

        $result['category'] = BaseUnsortedModel::CATEGORY_CODE_FORMS;

        return $result;
    }
}
