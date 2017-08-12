<?php
/**
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 * @link       http://github.com/LansoWeb/LosDomain
 */
namespace LosDomain\Options;

use Interop\Container\ContainerInterface;
use LosDomain\Service\Domain;
use Zend\ServiceManager\Factory\FactoryInterface;

class DomainOptionsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $domain = Domain::getStaticDomain();
        $alias = null;
        $layout = null;
        if (!empty($domain) && array_key_exists($domain, $config)) {
            $alias = $config[$domain]['alias'] ?: null;
            $layout = $config[$domain]['layout'] ?: null;
        }

        return new DomainOptions($domain, $alias, $layout);
    }
}
