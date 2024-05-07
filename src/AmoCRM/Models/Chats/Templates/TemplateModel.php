<?php

declare(strict_types=1);

namespace AmoCRM\Models\Chats\Templates;

use AmoCRM\Collections\Chats\Templates\Buttons\ButtonsCollection;
use AmoCRM\Collections\Chats\Templates\ReviewsCollection;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Traits\RequestIdTrait;

use function is_array;

/**
 * Class TemplateModel
 *
 * @package AmoCRM\Models\Chats\Templates
 */
class TemplateModel extends BaseApiModel implements HasIdInterface
{
    use RequestIdTrait;

    const TYPE_AMOCRM = 'amocrm';
    const TYPE_WABA = 'waba';

    public const CATEGORY_UTILITY = 'UTILITY';
    public const CATEGORY_AUTHENTICATION = 'AUTHENTICATION';
    public const CATEGORY_MARKETING = 'MARKETING';

    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var int|null
     */
    protected $accountId;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $content;

    /**
     * @var ButtonsCollection
     */
    protected $buttons;

    /**
     * @var int|null
     */
    protected $createdAt;

    /**
     * @var int|null
     */
    protected $updatedAt;

    /**
     * @var bool|null
     */
    protected $isEditable;

    /**
     * @var string|null
     */
    protected $externalId;

    /**
     * @var AttachmentModel|null
     */
    protected $attachment;

    /** @var string|null */
    protected $wabaCategory = null;

    /** @var string|null */
    protected $wabaLanguage = null;

    /** @var string|null */
    protected $wabaHeader = null;

    /** @var string|null */
    protected $wabaFooter = null;

    /** @var string */
    protected $type = self::TYPE_AMOCRM;

    /** @var array|null */
    protected $wabaExamples = null;

    /**
     * @var ReviewsCollection|null
     */
    protected $reviews = null;

    /**
     * @param array $template
     *
     * @return self
     */
    public static function fromArray(array $template): TemplateModel
    {
        $model = new static();

        if (isset($template['id'])) {
            $model->setId((int)$template['id']);
        }

        if (isset($template['account_id'])) {
            $model->setAccountId((int)$template['account_id']);
        }

        if (isset($template['name'])) {
            $model->setName($template['name']);
        }

        if (isset($template['content'])) {
            $model->setContent($template['content']);
        }

        if (isset($template['created_at'])) {
            $model->setCreatedAt((int)$template['created_at']);
        }

        if (isset($template['updated_at'])) {
            $model->setUpdatedAt((int)$template['updated_at']);
        }

        if (isset($template['is_editable'])) {
            $model->setIsEditable((bool)$template['is_editable']);
        }

        if (isset($template['external_id'])) {
            $model->setExternalId($template['external_id']);
        }

        $buttonsCollection = isset($template['buttons']) && !empty($template['buttons']) && is_array($template['buttons'])
            ? ButtonsCollection::fromArray($template['buttons'])
            : new ButtonsCollection();

        $model->setButtons($buttonsCollection);

        $attachmentModel = isset($template['attachment']) && !empty($template['attachment']) && is_array($template['attachment'])
            ? AttachmentModel::fromArray($template['attachment'])
            : null;

        $model->setAttachment($attachmentModel);

        if (isset($template['type'])) {
            $model->setType($template['type']);
        }

        if (isset($template['waba_header'])) {
            $model->setWabaHeader($template['waba_header']);
        }

        if (isset($template['waba_footer'])) {
            $model->setWabaFooter($template['waba_footer']);
        }

        if (isset($template['waba_language'])) {
            $model->setWabaLanguage($template['waba_language']);
        }

        if (isset($template['waba_category'])) {
            $model->setWabaCategory($template['waba_category']);
        }

        if (isset($template['waba_examples'])) {
            $model->setWabaExamples($template['waba_examples']);
        }

        if (isset($template['_embedded']['reviews']) && is_array($template['_embedded']['reviews'])) {
            $model->setReviews(ReviewsCollection::fromArray($template['_embedded']['reviews']));
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $buttons = $this->getButtons() !== null && !$this->getButtons()->isEmpty()
            ? $this->getButtons()->toArray()
            : null;

        return [
            'id' => $this->getId(),
            'account_id' => $this->getAccountId(),
            'name' => $this->getName(),
            'content' => $this->getContent(),
            'buttons' => $buttons,
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'is_editable' => $this->getIsEditable(),
            'attachment' => $this->getAttachment() ? $this->getAttachment()->toArray() : null,
            'external_id' => $this->getExternalId(),
            'request_id' => $this->getRequestId(),
            'type' => $this->getType(),
            'waba_header' => $this->getWabaHeader(),
            'waba_footer' => $this->getWabaFooter(),
            'waba_category' => $this->getWabaCategory(),
            'waba_language' => $this->getWabaLanguage(),
            'waba_examples' => $this->getWabaExamples(),
            'reviews' => $this->getReviews() ? $this->getReviews()->toArray() : null
        ];
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }
        $buttons = $this->getButtons() !== null && !$this->getButtons()->isEmpty()
            ? $this->getButtons()->toArray()
            : null;

        $result = [
            'name' => $this->getName(),
            'content' => $this->getContent(),
            'buttons' => $buttons,
            'is_editable' => $this->getIsEditable(),
            'external_id' => $this->getExternalId(),
            'request_id' => $this->getRequestId(),
            'attachment' => $this->getAttachment() ? $this->getAttachment()->toApi() : null,
            'type' => $this->getType(),
        ];

        if ($this->getWabaHeader()) {
            $result['waba_header'] = $this->getWabaHeader();
        }

        if ($this->getWabaFooter()) {
            $result['waba_footer'] = $this->getWabaFooter();
        }

        if ($this->getWabaCategory()) {
            $result['waba_category'] = $this->getWabaCategory();
        }

        if ($this->getWabaLanguage()) {
            $result['waba_language'] = $this->getWabaLanguage();
        }

        if ($this->getWabaExamples()) {
            $result['waba_examples'] = $this->getWabaExamples();
        }

        if ($this->getId() !== null) {
            $result['id'] = $this->getId();
        }

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
     * @param int|null $id
     *
     * @return TemplateModel
     */
    public function setId(?int $id): TemplateModel
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    /**
     * @param int|null $accountId
     *
     * @return TemplateModel
     */
    public function setAccountId(?int $accountId): TemplateModel
    {
        $this->accountId = $accountId;

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
     *
     * @return TemplateModel
     */
    public function setName(?string $name): TemplateModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     *
     * @return TemplateModel
     */
    public function setContent(?string $content): TemplateModel
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return ButtonsCollection|null
     */
    public function getButtons(): ?ButtonsCollection
    {
        return $this->buttons;
    }

    /**
     * @param ButtonsCollection|null $buttons
     *
     * @return TemplateModel
     */
    public function setButtons(?ButtonsCollection $buttons): TemplateModel
    {
        $this->buttons = $buttons;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    /**
     * @param int|null $createdAt
     *
     * @return TemplateModel
     */
    public function setCreatedAt(?int $createdAt): TemplateModel
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getUpdatedAt(): ?int
    {
        return $this->updatedAt;
    }

    /**
     * @param int|null $updatedAt
     *
     * @return TemplateModel
     */
    public function setUpdatedAt(?int $updatedAt): TemplateModel
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsEditable(): ?bool
    {
        return $this->isEditable;
    }

    /**
     * @param bool|null $isEditable
     *
     * @return TemplateModel
     */
    public function setIsEditable(?bool $isEditable): TemplateModel
    {
        $this->isEditable = $isEditable;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    /**
     * @param string|null $externalId
     *
     * @return TemplateModel
     */
    public function setExternalId(?string $externalId): TemplateModel
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * @return AttachmentModel|null
     */
    public function getAttachment(): ?AttachmentModel
    {
        return $this->attachment;
    }

    /**
     * @param AttachmentModel|null $attachment
     *
     * @return TemplateModel
     */
    public function setAttachment(?AttachmentModel $attachment): TemplateModel
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * @param string|null $wabaCategory
     * @return TemplateModel
     */
    public function setWabaCategory(?string $wabaCategory): TemplateModel
    {
        $this->wabaCategory = $wabaCategory;
        return $this;
    }

    /**
     * @param string|null $wabaLanguage
     * @return TemplateModel
     */
    public function setWabaLanguage(?string $wabaLanguage): TemplateModel
    {
        $this->wabaLanguage = $wabaLanguage;
        return $this;
    }

    /**
     * @param string|null $wabaHeader
     * @return TemplateModel
     */
    public function setWabaHeader(?string $wabaHeader): TemplateModel
    {
        $this->wabaHeader = $wabaHeader;
        return $this;
    }

    /**
     * @param string|null $wabaFooter
     * @return TemplateModel
     */
    public function setWabaFooter(?string $wabaFooter): TemplateModel
    {
        $this->wabaFooter = $wabaFooter;
        return $this;
    }

    /**
     * @param string $type
     * @return TemplateModel
     */
    public function setType(string $type): TemplateModel
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param array|null $examples
     * @return TemplateModel
     */
    public function setWabaExamples(?array $examples): TemplateModel
    {
        $this->wabaExamples = $examples;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getWabaExamples(): ?array
    {
        return $this->wabaExamples;
    }

    /**
     * @return string|null
     */
    public function getWabaCategory(): ?string
    {
        return $this->wabaCategory;
    }

    /**
     * @return string|null
     */
    public function getWabaLanguage(): ?string
    {
        return $this->wabaLanguage;
    }

    /**
     * @return string|null
     */
    public function getWabaHeader(): ?string
    {
        return $this->wabaHeader;
    }

    /**
     * @return string|null
     */
    public function getWabaFooter(): ?string
    {
        return $this->wabaFooter;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param ReviewsCollection|null $reviews
     * @return TemplateModel
     */
    public function setReviews(?ReviewsCollection $reviews): TemplateModel
    {
        $this->reviews = $reviews;
        return $this;
    }

    /**
     * @return ReviewsCollection|null
     */
    public function getReviews(): ?ReviewsCollection
    {
        return $this->reviews;
    }

    public static function getAvailableWith(): array
    {
        return ['reviews'];
    }
}
