<?php
/**
 * Module file
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE BSD-3 License
 * @link       http://github.com/LansoWeb/LosDomain
 */
namespace LosDomain;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;
use LosDomain\Options\ModuleOptions;
use LosDomain\Service\Domain as DomainService;
use LosDomain\Options\DomainOptions;
use Zend\Stdlib\ArrayUtils;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Module class
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE BSD-3 License
 * @link       http://github.com/LansoWeb/LosDomain
 */
class Module implements AutoloaderProviderInterface
{
    /**
     * Module ini function that import the current domain files or it's alias
     *
     * @param ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        $events = $moduleManager->getEventManager();

        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, function ($e) {
            $configListener = $e->getConfigListener();
            $config = $configListener->getMergedConfig(false);
            $domain = DomainService::getDomain();

            $domainDir = null;

            if (!isset($config[$domain])) {
                if (isset($config['losdomain']['domain_dir'])) {
                    $domainDir = $config['losdomain']['domain_dir'];
                    $domainConfig = DomainOptions::importDomain($domainDir, $domain);
                    $config = ArrayUtils::merge($config, $domainConfig);
                    $configListener->setMergedConfig($config);
                }
            }

            if ($domainDir !== null && isset($config[$domain]) && array_key_exists('alias', $config[$domain])) {
                $alias = $config[$domain]['alias'];

                $aliasConfig = DomainOptions::importDomain($domainDir, $alias);
                $config[$domain] = $aliasConfig[$alias];
                $config = ArrayUtils::merge($config, $aliasConfig);
                unset($config[$alias]);
                $configListener->setMergedConfig($config);
            }
        });
    }

    public function onBootstrap($e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function ($e) {
            if (!isset($_SERVER['HTTP_HOST'])) {
                return;
            }
            $controller = $e->getTarget();
            $domainService = $e->getApplication()->getServiceManager()->get('losdomain.service');

            $layout = $domainService->getLayout();
            if ($layout) {
                $controller->layout($layout);
            }
        }, 100);
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'losdomain_options' => function (ServiceLocatorInterface $sl) {
                    $config = $sl->get('Configuration');

                    return new ModuleOptions(isset($config['losdomain']) ? $config['losdomain'] : []);
                },
                'LosDomain\Service\Domain' => function (ServiceLocatorInterface $sl) {
                    $domain = new DomainService();
                    $domain->setServiceLocator($sl);

                    return $domain;
                },
                'LosDomain\Options\DomainOptions' => function (ServiceLocatorInterface $sl) {
                    $service = $sl->get('losdomain.service');

                    return $service->getDomainOptions();
                },
            ],
            'aliases' => [
                'losdomain.service' => 'LosDomain\Service\Domain',
                'losdomain.domain.options' => 'LosDomain\Options\DomainOptions',
            ]
        ];
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__.'/../../autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__,
                ],
            ],
        ];
    }

    public function getConfig()
    {
        return include __DIR__.'/../../config/module.config.php';
    }
}
