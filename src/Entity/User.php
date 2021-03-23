<?php

namespace Spoonity\Entity;

/**
 * Class User
 * @package Spoonity\Entity
 */
class User
{
    /** @var int|null */
    private $userId;

    /** @var string|null */
    private $emailAddress;

    /** @var string|null */
    private $firstName;

    /** @var string|null */
    private $lastName;

    /** @var string[] */
    private $phoneNumbers;

    /** @var string[] */
    private $devices;

    /**
     * User constructor.
     * @param int|null $userId
     * @param string|null $emailAddress
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string[] $phoneNumbers
     * @param string[] $devices
     */
    public function __construct(?int $userId, ?string $emailAddress, ?string $firstName = null, ?string $lastName = null, array $phoneNumbers = [], array $devices = [])
    {
        $this->userId = $userId;
        $this->emailAddress = $emailAddress;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phoneNumbers = $phoneNumbers;
        $this->devices = $devices;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @return string|null
     */
    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string[]
     */
    public function getPhoneNumbers(): array
    {
        return $this->phoneNumbers;
    }

    /**
     * @return string[]
     */
    public function getDevices(): array
    {
        return $this->devices;
    }}
