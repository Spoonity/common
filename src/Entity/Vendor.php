<?php

namespace Spoonity\Entity;

/**
 * Class Vendor
 * @package Spoonity\Entity
 */
class Vendor
{
    /** @var int */
    private $vendorId;

    /** @var string */
    private $name;

    /** @var string|null */
    private $supportEmail;

    /**
     * Vendor constructor.
     * @param int $vendorId
     * @param string $name
     * @param string|null $supportEmail
     */
    public function __construct(int $vendorId, string $name, ?string $supportEmail = null)
    {
        $this->vendorId = $vendorId;
        $this->name = $name;
        $this->supportEmail = $supportEmail;
    }

    /**
     * @return int
     */
    public function getVendorId(): int
    {
        return $this->vendorId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getSupportEmail(): ?string
    {
        return $this->supportEmail;
    }
}
