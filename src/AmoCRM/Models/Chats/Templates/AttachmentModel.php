<?php

declare(strict_types=1);

namespace AmoCRM\Models\Chats\Templates;

use AmoCRM\Enum\Chats\Templates\Attachment\TypesEnum;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Contracts\Support\Arrayable;

/**
 * Class AttachmentModel
 *
 * @package AmoCRM\Models\Chats\Templates\Buttons
 */
class AttachmentModel extends BaseApiModel implements Arrayable
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $type;

    /**
     * @param array $attachment
     * @return AttachmentModel
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $attachment): AttachmentModel
    {
        if (empty($attachment['type']) || !in_array($attachment['type'], TypesEnum::getAll(), true)) {
            throw new InvalidArgumentException('Attachment type missed');
        }

        $model = new self();
        $model->setId($attachment['id'])
            ->setName($attachment['name'])
            ->setType($attachment['type']);

        return $model;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return AttachmentModel
     */
    public function setId(string $id): AttachmentModel
    {
        $this->id = $id;

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
     * @return AttachmentModel
     */
    public function setName(string $name): AttachmentModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return AttachmentModel
     */
    public function setType(string $type): AttachmentModel
    {
        $this->type = $type;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'type' => $this->getType(),
        ];
    }
}
