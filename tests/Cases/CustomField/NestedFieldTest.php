<?php

declare(strict_types=1);

namespace Cases\CustomField;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\CustomFields\NestedModel;
use PHPUnit\Framework\TestCase;

class NestedFieldTest extends TestCase
{
    /**
     * @return array
     */
    public function getInvalidRequestIdData(): array
    {
        return [
            ['$test'], // spec symbols
            ['request_id'], // underscore
            ['идентификатор'], // cyrillic
            ['request id'], // whitespace
        ];
    }

    /**
     * @dataProvider getInvalidRequestIdData
     *
     * @param string $requestId
     *
     * @throws InvalidArgumentException
     */
    public function testInvalidRequestId(string $requestId): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new NestedModel())->setRequestId($requestId);
    }

    /**
     * @dataProvider getInvalidRequestIdData
     *
     * @param string $requestId
     *
     * @throws InvalidArgumentException
     */
    public function testInvalidParentRequestId(string $requestId): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new NestedModel())->setParentRequestId($requestId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFullToApiMethod(): void
    {
        $correctResult = [
            'request_id'        => 'Request1',
            'parent_request_id' => 'ParentRequest1',
            'sort'              => 1,
            'value'             => 'value123',
            'id'                => 123,
            'parent_id'         => 1234,
        ];

        $model = (new NestedModel())
            ->setRequestId($correctResult['request_id'])
            ->setParentRequestId($correctResult['parent_request_id'])
            ->setSort($correctResult['sort'])
            ->setValue($correctResult['value'])
            ->setId($correctResult['id'])
            ->setParentId($correctResult['parent_id']);

        $this->assertEqualsCanonicalizing($correctResult, $model->toApi());
    }
}
