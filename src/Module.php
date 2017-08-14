<?php
/**
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 * @link       http://github.com/LansoWeb/LosDomain
 */
namespace LosDomain;

use LosDomain\Options\DomainOptions;
use LosDomain\Service\Domain;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;

/**
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 * @link       http://github.com/LansoWeb/LosDomain
 */
class Module implements ConfigProviderInterface
{
    /**
     * Module ini function that import the current domain files or it's alias
     *
     * @param ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        $events = $moduleManager->getEventManager();

        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, function (EventInterface $e) {
            $configListener = $e->getConfigListener();
            $config = $configListener->getMergedConfig(false);
            $domain = Domain::getStaticDomain();

            $domainDir = null;

            if (!isset($config[$domain]) && isset($config['losdomain']['domain_dir'])) {
                $domainDir = $config['losdomain']['domain_dir'];
                $domainConfig = DomainOptions::importDomain($domainDir, $domain);
                $config = ArrayUtils::merge($config, $domainConfig);
                $configListener->setMergedConfig($config);
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

    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            AbstractController::class,
            'dispatch',
            function (EventInterface $e) {

            if (!isset($_SERVER['HTTP_HOST'])) {
                return;
            }
            $controller = $e->getTarget();
            $domainService = $e->getApplication()->getServiceManager()->get(Domain::class);

            $layout = $domainService->getLayout();
            if ($layout) {
                $controller->layout($layout);
            }
        }, 100);
    }

    public function getConfig()
    {
        return include __DIR__.'/../config/module.config.php';
    }
}
