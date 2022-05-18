<?php

namespace AmoCRM\Models\AccountSettings;

use AmoCRM\Contracts\Support\Arrayable;

class InvoicesSettings implements Arrayable
{
    /**
     * @var string|null
     */
    protected $lang;

    public function __construct(
        ?string $lang
    ) {
        $this->lang = $lang;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'lang' => $this->getLang(),
        ];
    }

    /**
     * @return string|null
     */
    public function getLang(): ?string
    {
        return $this->lang;
    }
}
