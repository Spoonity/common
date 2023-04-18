<?php

namespace Spoonity\Common\Service\Abstraction;


use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BaseService
 * @package Spoonity\Common\Service\Abstraction
 */
abstract class BaseService
{
    /** @var ContainerInterface */
    private ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
