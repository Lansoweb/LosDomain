<?php
/**
 * Domain Options file
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 * @link       http://github.com/LansoWeb/LosDomain
 */
namespace LosDomain\Options;

use Zend\Stdlib\ArrayUtils;

/**
 * Domain Options class
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 * @link       http://github.com/LansoWeb/LosDomain
 */
class DomainOptions
{
    /**
     * The domain
     * @var string
     */
    protected $domain;

    /**
     * the layout to be used when using this domain
     * @var string
     */
    protected $layout;

    /**
     * If this domain is as alias to other. The module will import the configuration files from the alias
     * @var string
     */
    protected $alias;

    /**
     * DomainOptions constructor.
     * @param $domain
     * @param $alias
     * @param $layout
     */
    public function __construct($domain, $alias, $layout)
    {
        $this->domain = $domain;
        $this->alias = $alias;
        $this->layout = $layout;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Import the files form a domain (both global and local)
     *
     * @param  string $path   The path to the domains
     * @param  string $domain The domain
     * @return array  The merged array with the options
     */
    public static function importDomain($path, $domain)
    {
        $global = $path.DIRECTORY_SEPARATOR.$domain.DIRECTORY_SEPARATOR.'domain.global.php';
        $config = [];
        if (file_exists($global)) {
            $config = include $global;
        }

        $local = $path.DIRECTORY_SEPARATOR.$domain.DIRECTORY_SEPARATOR.'domain.local.php';
        if (file_exists($local)) {
            $config = ArrayUtils::merge($config, include $local);
        }

        return $config;
    }
}
