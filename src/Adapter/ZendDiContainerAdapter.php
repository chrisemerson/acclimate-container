<?php

namespace Acclimate\Container\Adapter;

use Acclimate\Container\Exception\ContainerException as AcclimateContainerException;
use Acclimate\Container\Exception\NotFoundException as AcclimateNotFoundException;
use Psr\Container\ContainerInterface;
use Zend\Di\LocatorInterface;

/**
 * An adapter from a Zend DIC to the standardized ContainerInterface
 */
class ZendDiContainerAdapter implements ContainerInterface
{
    /**
     * @var LocatorInterface A Zend DIC/ServiceLocator
     */
    private $container;

    /**
     * @param LocatorInterface $container A Zend DIC/ServiceLocator
     */
    public function __construct(LocatorInterface $container)
    {
        $this->container = $container;
    }

    public function get($id)
    {
        try {
            $result = $this->container->get($id);
        } catch (\Exception $prev) {
            throw AcclimateContainerException::fromPrevious($id, $prev);
        }

        if ($result === null) {
            throw AcclimateNotFoundException::fromPrevious($id);
        }

        return $result;
    }

    public function has($id): bool
    {
        return ($this->container->get($id) !== null);
    }
}
