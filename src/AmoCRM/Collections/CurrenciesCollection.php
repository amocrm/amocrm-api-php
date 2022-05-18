<?php

declare(strict_types=1);

namespace AmoCRM\AmoCRM\Collections;

use AmoCRM\AmoCRM\Models\CurrencyModel;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\Interfaces\HasPagesInterface;
use AmoCRM\Collections\Traits\PagesTrait;

/**
 * Class CurrenciesCollection
 *
 * @since Release Spring 2022
 * @package AmoCRM\Collections
 *
 * @method null|CurrencyModel current()
 * @method null|CurrencyModel last()
 * @method null|CurrencyModel first()
 * @method null|CurrencyModel offsetGet($offset)
 * @method self offsetSet($offset, CurrencyModel $value)
 * @method self prepend(CurrencyModel $value)
 * @method self add(CurrencyModel $value)
 * @method null|CurrencyModel getBy($key, $value)
 */
class CurrenciesCollection extends BaseApiCollection implements HasPagesInterface
{
    use PagesTrait;

    public const ITEM_CLASS = CurrencyModel::class;

    /**
     * @param string $code
     *
     * @return null|CurrencyModel
     */
    public function getByCode(string $code): ?CurrencyModel
    {
        return $this->getBy('code', $code);
    }
}
