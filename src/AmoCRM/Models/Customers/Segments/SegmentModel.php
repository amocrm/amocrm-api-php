<?php

namespace AmoCRM\Models\Customers\Segments;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Traits\RequestIdTrait;
use InvalidArgumentException;

use function is_null;
use function array_key_exists;

/**
 * Class SegmentModel
 *
 * @package AmoCRM\Models\Customers\Segments
 */
class SegmentModel extends BaseApiModel implements HasIdInterface
{
    use RequestIdTrait;

    public const SEGMENT_COLORS = [
        '10599d',
        '2176ff',
        '006acc',
        '07a0c3',
        '247ba0',
        '177e89',
        '046e8f',
        '598381',
        '0c7c59',
        '495f41',
        '00a44b',
        '08605f',
        'bf2600',
        '06d6a0',
        'e14945',
        '79b473',
        'ae003f',
        'a2ad59',
        'cd0f53',
        '8e936d',
        '832161',
        '2e5339',
        'bf126f',
        '6f7c12',
        'ff5376',
        'dd1c1a',
        'bb304e',
        '631d76',
        '9d2b32',
        '4a001f',
        'b118c8',
        '6a0f49',
        '6610f2',
        'b38a58',
        '8963ba',
        '4b3666',
        '932f6d',
        '6b2d5c',
        '6461a0',
        '4f517d',
    ];

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $createdAt;

    /**
     * @var int
     */
    protected $updatedAt;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var int|null
     */
    protected $customersCount;

    /**
     * @var CustomFieldsValuesCollection
     */
    protected $customFieldsValues;

    /**
     * @var array|null
     */
    protected $availableProductsPriceTypes;

    /**
     * @param array $segment
     *
     * @return self
     */
    public static function fromArray(array $segment): self
    {
        if (empty($segment['id'])) {
            throw new InvalidArgumentException('Segment id is empty in ' . json_encode($segment));
        }

        $segmentModel = (new self())
            ->setId((int)$segment['id']);

        if (!empty($segment['name'])) {
            $segmentModel->setName($segment['name']);
        }

        if (!empty($segment['color'])) {
            $segmentModel->setColor($segment['color']);
        }

        if (!empty($segment['created_at'])) {
            $segmentModel->setCreatedAt($segment['created_at']);
        }

        if (!empty($segment['updated_at'])) {
            $segmentModel->setUpdatedAt($segment['updated_at']);
        }

        if (array_key_exists('customers_count', $segment) && !is_null($segment['customers_count'])) {
            $segmentModel->setCustomersCount($segment['customers_count']);
        }

        if (!empty($segment['available_products_price_types'])) {
            $segmentModel->setAvailableProductsPriceTypes($segment['available_products_price_types']);
        }

        if (!empty($segment['custom_fields_values'])) {
            $valuesCollection = new CustomFieldsValuesCollection();
            $customFieldsValues = $valuesCollection->fromArray($segment['custom_fields_values']);
            $segmentModel->setCustomFieldsValues($customFieldsValues);
        }

        return $segmentModel;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'color' => $this->getColor(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'customers_count' => $this->getCustomersCount(),
            'available_products_price_types' => $this->getAvailableProductsPriceTypes(),
            'custom_fields_values' => is_null($this->getCustomFieldsValues())
                ? null
                : $this->getCustomFieldsValues()->toArray(),
        ];
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return SegmentModel
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SegmentModel
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string|null $requestId
     * @return array
     */
    public function toApi(?string $requestId = "0"): array
    {
        $result = [];

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        if (!is_null($this->getName())) {
            $result['name'] = $this->getName();
        }

        if (!is_null($this->getColor())) {
            $result['color'] = $this->getColor();
        }

        if (!is_null($this->getAvailableProductsPriceTypes())) {
            $result['available_products_price_types'] = $this->getAvailableProductsPriceTypes();
        }

        if (!is_null($this->getCustomFieldsValues())) {
            $result['custom_fields_values'] = $this->getCustomFieldsValues()->toApi();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }


    /**
     * @return null|int
     */
    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    /**
     * @param int $timestamp
     *
     * @return self
     */
    public function setCreatedAt(int $timestamp): self
    {
        $this->createdAt = $timestamp;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getUpdatedAt(): ?int
    {
        return $this->updatedAt;
    }

    /**
     * @param int $timestamp
     *
     * @return self
     */
    public function setUpdatedAt(int $timestamp): self
    {
        $this->updatedAt = $timestamp;

        return $this;
    }


    public static function getAvailableWith(): array
    {
        return [];
    }

    /**
     * @return null|string
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return SegmentModel
     */
    public function setColor(string $color): self
    {
        if (!in_array($color, self::SEGMENT_COLORS, true)) {
            $color = '10599d';
        }

        $this->color = $color;

        return $this;
    }

    /**
     * @return int
     */
    public function getCustomersCount(): ?int
    {
        return $this->customersCount;
    }

    /**
     * @param int $customersCount
     * @return SegmentModel
     */
    public function setCustomersCount(int $customersCount): self
    {
        $this->customersCount = $customersCount;

        return $this;
    }

    /**
     * @return CustomFieldsValuesCollection|null
     */
    public function getCustomFieldsValues(): ?CustomFieldsValuesCollection
    {
        return $this->customFieldsValues;
    }

    /**
     * @param CustomFieldsValuesCollection|null $values
     *
     * @return self
     */
    public function setCustomFieldsValues(?CustomFieldsValuesCollection $values): self
    {
        $this->customFieldsValues = $values;

        return $this;
    }

    /**
     * @return null|array
     */
    public function getAvailableProductsPriceTypes(): ?array
    {
        return $this->availableProductsPriceTypes;
    }

    /**
     * @param null|array $availableProductsPriceTypes
     * @return SegmentModel
     */
    public function setAvailableProductsPriceTypes(?array $availableProductsPriceTypes): self
    {
        $this->availableProductsPriceTypes = $availableProductsPriceTypes;

        return $this;
    }
}
