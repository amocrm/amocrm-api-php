<?php

declare(strict_types=1);

namespace AmoCRM\Models\Sources;

use AmoCRM\Contracts\Support\Arrayable;
use AmoCRM\Exceptions\NotAvailableForActionException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Traits\RequestIdTrait;

class WebsiteButtonModel extends BaseApiModel implements Arrayable
{
    use RequestIdTrait;

    private const SCRIPTS = 'scripts';

    /**
     * @var int
     */
    private $accountId;

    /**
     * @var int
     */
    private $sourceId;

    /**
     * @var bool
     */
    private $isDuplicationControlEnabled;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int|null
     */
    private $buttonId;

    /**
     * @var int|null
     */
    private $pipelineId;

    /**
     * @var string|null
     */
    private $creationStatus;

    /**
     * @var string|null
     */
    private $script;


    /**
     * @param array $button
     *
     * @return self
     */
    public static function fromArray(array $button): self
    {
        return (new self())
            ->setAccountId((int)$button['account_id'])
            ->setSourceId((int)$button['source_id'])
            ->setIsDuplicationControlEnabled((bool)$button['is_duplication_control_enabled'])
            ->setName((string)$button['name'])
            ->setButtonId((int)($button['button_id'] ?? 0) ?: null)
            ->setPipelineId(!empty($button['pipeline_id']) ? $button['pipeline_id'] : null)
            ->setCreationStatus(!empty($button['creation_status']) ? $button['creation_status'] : null)
            ->setScript(!empty($button['script']) ? $button['script'] : null);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'account_id' => $this->getAccountId(),
            'source_id' => $this->getSourceId(),
            'button_id' => $this->getButtonId(),
            'is_duplication_control_enabled' => $this->isDuplicationControlEnabled(),
            'name' => $this->getName(),
            'creation_status' => $this->getCreationStatus(),
            'pipeline_id' => $this->getPipelineId(),
            'script' => $this->getScript(),
        ];
    }

    /**
     * @param string|null $requestId
     *
     * @return array
     * @throws NotAvailableForActionException
     */
    public function toApi(?string $requestId = '0'): array
    {
        throw new NotAvailableForActionException();
    }

    /**
     * @return array
     */
    public static function getAvailableWith(): array
    {
        return [
            self::SCRIPTS,
        ];
    }

    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     *
     * @return WebsiteButtonModel
     */
    public function setAccountId(int $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return int
     */
    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    /**
     * @param int $sourceId
     *
     * @return WebsiteButtonModel
     */
    public function setSourceId(int $sourceId): self
    {
        $this->sourceId = $sourceId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getButtonId(): ?int
    {
        return $this->buttonId;
    }

    /**
     * @param int|null $buttonId
     *
     * @return WebsiteButtonModel
     */
    public function setButtonId(?int $buttonId): self
    {
        $this->buttonId = $buttonId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDuplicationControlEnabled(): bool
    {
        return $this->isDuplicationControlEnabled;
    }

    /**
     * @param bool $isDuplicationControlEnabled
     *
     * @return WebsiteButtonModel
     */
    public function setIsDuplicationControlEnabled(bool $isDuplicationControlEnabled): self
    {
        $this->isDuplicationControlEnabled = $isDuplicationControlEnabled;

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
     * @return WebsiteButtonModel
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreationStatus(): ?string
    {
        return $this->creationStatus;
    }

    /**
     * @param string|null $creationStatus
     * @return WebsiteButtonModel
     */
    public function setCreationStatus(?string $creationStatus): self
    {
        $this->creationStatus = $creationStatus;

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
     * @return WebsiteButtonModel
     */
    public function setPipelineId(?int $pipelineId): self
    {
        $this->pipelineId = $pipelineId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getScript(): ?string
    {
        return $this->script;
    }

    /**
     * @param string|null $script
     *
     * @return WebsiteButtonModel
     */
    public function setScript(?string $script): self
    {
        $this->script = $script;

        return $this;
    }
}
