<?php
/**
 * Domain Options file
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE BSD-3 License
 * @link       http://github.com/LansoWeb/LosDomain
 */
namespace LosDomain\Options;

use Zend\Stdlib\AbstractOptions;
use Zend\Stdlib\ArrayUtils;

/**
 * Domain Options class
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE BSD-3 License
 * @link       http://github.com/LansoWeb/LosDomain
 */
class DomainOptions extends AbstractOptions
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
     * Constructor
     * @param array $options Array with the options
     * @param string $domain The domain
     */
    public function __construct($options, $domain)
    {
        parent::__construct($options);
        $this->domain = $domain;
    }

    public function getDomain()
    {
        return $this->domain;
    }
    
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    public function getAlias()
    {
        return $this->alias;
    }
    
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * Import the files form a domain (both global and local)
     * 
     * @param string $path The path to the domains
     * @param string $domain The domain
     * @return array The merged array with the options
     */
    public static function importDomain($path, $domain)
    {
        $global = $path . DIRECTORY_SEPARATOR . $domain . DIRECTORY_SEPARATOR . 'domain.global.php';
        $config = [];
        if (file_exists($global)) {
            $config = include $global;
        }
    
        $local = $path . DIRECTORY_SEPARATOR . $domain . DIRECTORY_SEPARATOR . 'domain.local.php';
        if (file_exists($local)) {
            $config = ArrayUtils::merge($config, include $local);
        }
        
        return $config;
    }
    
}
