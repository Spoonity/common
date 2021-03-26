<?php

namespace Spoonity\Command\Abstraction;

use Doctrine\Persistence\ManagerRegistry;
use Spoonity\Service\AccountsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BaseCommand
 * @package Spoonity\Command\Abstraction
 */
abstract class BaseCommand extends Command
{
    /** @var ContainerInterface */
    private $container;

    /** @var AccountsService */
    private $accountsService;

    /**
     * BaseCommand constructor.
     * @param ContainerInterface $container
     * @param AccountsService $accountsService
     */
    public function __construct(ContainerInterface $container, AccountsService $accountsService)
    {
        $this->container = $container;
        $this->accountsService = $accountsService;

        parent::__construct();
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
