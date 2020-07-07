<?php

declare(strict_types=1);

namespace AmoCRM\AmoCRM\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Class AccountSubdomainModel
 *
 * @package AmoCRM\AmoCRM\Models
 */
class AccountSubdomainModel implements Arrayable, Jsonable
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $subdomain;

    /** @var string */
    protected $domain;

    /** @var string */
    protected $topLevelDomain;

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $subdomain
     *
     * @return $this
     */
    public function setSubdomain(string $subdomain): self
    {
        $this->subdomain = $subdomain;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubdomain(): string
    {
        return $this->subdomain;
    }

    /**
     * @param string $domain
     *
     * @return $this
     */
    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $topLevelDomain
     *
     * @return $this
     */
    public function setTopLevelDomain(string $topLevelDomain): self
    {
        $this->topLevelDomain = $topLevelDomain;

        return $this;
    }

    /**
     * @return string
     */
    public function getTopLevelDomain(): string
    {
        return $this->topLevelDomain;
    }

    /**
     * @param array $accountSubdomain
     *
     * @return static
     */
    public static function fromArray(array $accountSubdomain): self
    {
        $accountSubdomainModel = new self();
        $accountSubdomainModel->setId((int)$accountSubdomain['id'])
            ->setSubdomain($accountSubdomain['subdomain'])
            ->setDomain($accountSubdomain['domain'])
            ->setTopLevelDomain($accountSubdomain['top_level_domain']);

        return $accountSubdomainModel;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'               => $this->getId(),
            'subdomain'        => $this->getSubdomain(),
            'domain'           => $this->getDomain(),
            'top_level_domain' => $this->getTopLevelDomain(),
        ];
    }

    /**
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}