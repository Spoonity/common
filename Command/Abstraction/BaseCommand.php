<?php

namespace Spoonity\Common\Command\Abstraction;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BaseCommand
 * @package Spoonity\Common\Command\Abstraction
 */
abstract class BaseCommand extends Command
{
    /** @var ContainerInterface */
    private ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct();
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
