<?php
namespace LosDomain\Service;

use Interop\Container\ContainerInterface;
use LosDomain\Options\DomainOptions;
use Zend\ServiceManager\Factory\FactoryInterface;

class DomainFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Domain($container->get(DomainOptions::class));
    }
}
