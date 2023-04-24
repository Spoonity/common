<?php

namespace Spoonity\Entity;

/**
 * Class Customer
 * @package Spoonity\Entity
 */
class Customer
{
    /** @var int|null  */
    private ?int $id;

    /** @var string  */
    private string $firstName;

    /** @var string  */
    private string $lastName;

    /** @var string  */
    private string $emailAddress;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return $this->getId();
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->getFirstName();
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setFirstName(string $name): self
    {
        $this->firstName = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setLastName(string $name): self
    {
        $this->lastName = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     * @return $this
     */
    public function setEmailAddress(string $emailAddress): self
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }
}
