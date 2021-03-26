<?php

namespace Spoonity\Service\Abstraction;

use Doctrine\Persistence\ManagerRegistry;
use Spoonity\Service\AccountsService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BaseService
 * @package Spoonity\Service\Abstraction
 */
abstract class BaseService
{
    /** @var ContainerInterface */
    private $container;

    /** @var AccountsService */
    private $accountsService;

    /**
     * Created constructor.
     * @param ContainerInterface $container
     * @param AccountsService $accountsService
     */
    public function __construct(ContainerInterface $container, AccountsService $accountsService)
    {
        $this->container = $container;
        $this->accountsService = $accountsService;
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return ManagerRegistry
     */
    protected function getDoctrine(): ManagerRegistry
    {
        return $this->getContainer()->get('doctrine');
    }

    /**
     * @return AccountsService
     */
    protected function getAccountsService(): AccountsService
    {
        return $this->accountsService;
    }
}
