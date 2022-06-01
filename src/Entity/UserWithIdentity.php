<?php

namespace Spoonity\Entity;

use Spoonity\Entity\User;

/**
 * Class UserWithIdentity
 * @package Spoonity\Entity
 */
class UserWithIdentity extends User
{
    /** @var int  */
    private int $identityId;

    /**
     * @return int
     */
    public function getIdentityId(): int
    {
        return $this->identityId;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setIdentityId(int $id): self
    {
        $this->identityId = $id;

        return $this;
    }
}
