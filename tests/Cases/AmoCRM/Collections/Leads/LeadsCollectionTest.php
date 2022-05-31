<?php

declare(strict_types=1);

namespace Cases\AmoCRM\Collections\Leads;

use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\LeadModel;
use PHPUnit\Framework\TestCase;

use function time;

class LeadsCollectionTest extends TestCase
{
    /** @var LeadsCollection */
    private $collection;

    public function setUp(): void
    {
        $this->collection = new LeadsCollection();
    }

    public function getItemsCollectionDataProvider(): array
    {
        return [
            [
                [
                    // valid structure
                    [
                        'id' => 111,
                        'name' => 'Test lead name',
                        'price' => 10000,
                        'responsible_user_id' => 929393,
                        'group_id' => 1111,
                        'status_id' => 222222,
                        'pipeline_id' => 3333333,
                        'created_by' => 123,
                        'created_at' => time(),
                    ],
                    [
                        'id' => 222,
                        'price' => 10000,
                    ],
                ],
                2,
                null,
            ],
            [
                [
                    // invalid structure without id - check InvalidArgumentException
                    [
                        'name' => 'Test lead name',
                    ],
                ],
                1,
                InvalidArgumentException::class,
            ],
        ];
    }

    /**
     * @covers       \AmoCRM\Collections\Leads\LeadsCollection::fromArray
     * @covers       \AmoCRM\Models\LeadModel::fromArray
     *
     * @dataProvider getItemsCollectionDataProvider
     *
     * @param array $items
     * @param int $itemsCount
     * @param null|string $exceptionClassName
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function testBuild(
        array $items,
        int $itemsCount,
        ?string $exceptionClassName
    ): void {
        if ($exceptionClassName !== null) {
            $this->expectException($exceptionClassName);
        }

        $collection = $this->collection::fromArray($items);
        $this->assertSame($itemsCount, $collection->count());

        // check getting first model
        $item = $collection->first();
        $this->assertInstanceOf(LeadModel::class, $item);

        // check clear offsetUnset
        $collection->clear();
        $this->assertSame(0, $collection->count());

        // check fill models to collection
        foreach ($items as $item) {
            $collection->add(LeadModel::fromArray($item));
        }
    }
}
