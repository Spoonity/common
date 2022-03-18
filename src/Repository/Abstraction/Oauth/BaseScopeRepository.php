<?php

namespace Spoonity\Repository\Abstraction\Oauth;

use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Spoonity\Entity\Oauth\Scope;
use Spoonity\Repository\Abstraction\BaseRepository;

/**
 * Class BaseScopeRepository
 * @package Spoonity\Repository\Abstraction\Oauth
 */
abstract class BaseScopeRepository extends BaseRepository implements ScopeRepositoryInterface
{
    /**
     * @param string $identifier
     * @return Scope|null
     */
    public function findOneByIdentifier(string $identifier): ?Scope
    {
        return $this->findOneBy([
            'identifier' => $identifier
        ]);
    }

    /**
     * @param $identifier
     * @return Scope|null
     */
    public function getScopeEntityByIdentifier($identifier): ?Scope
    {
        return $this->findOneByIdentifier($identifier);
    }
}
