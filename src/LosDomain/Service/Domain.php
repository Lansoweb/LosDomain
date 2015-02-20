<?php
/**
 * Domain Service file
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE BSD-3 License
 * @link       http://github.com/LansoWeb/LosDomain
 */
namespace LosDomain\Service;

use Zend\ServiceManager\ServiceLocatorAwareTrait;
use LosDomain\Options\DomainOptions;

/**
 * Domain Service class
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE BSD-3 License
 * @link       http://github.com/LansoWeb/LosDomain
 */
class Domain
{
    use ServiceLocatorAwareTrait;

    /**
     * The domain
     * @var string
     */
    protected $domain = null;

    /**
     * The domain options
     * @var \LosDomain\Options\DomainOptions
     */
    protected $options;

    /**
     * The contructor. If not has a domain as parameter, it will detect it.
     *
     * @param string $domain
     */
    public function __construct($domain = null)
    {
        if ($domain === null) {
            $domain = self::getDomain();
        }
        $this->setDomain($domain);
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Gets the domain. If called statically, it will return the HTTP_HOST from $_SERVER. If not, will return the domain.
     *
     * @return null|string
     */
    public static function getDomain()
    {
        $isStatic = !(isset($this) && get_class($this) == __CLASS__);

        if ($isStatic) {
            return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
        }

        if ($this->domain === null) {
            $this->domain = self::getDomain();
        }

        return $this->domain;
    }

    /**
     * Returns the DomainOptions object. Create one if empty.
     *
     * @return \LosDomain\Options\DomainOptions
     */
    public function getDomainOptions()
    {
        if ($this->options === null) {
            $config = $this->getServiceLocator()->get('Config');
            $this->options = new DomainOptions(isset($config[$this->domain]) ? $config[$this->domain] : [], $this->domain);
        }

        return $this->options;
    }

    /**
     * Static function that sets the environment variable LOSDOMAIN_ENV.
     */
    public static function setDomainEnv()
    {
        //If already defined (console for example) use it
        if (getenv('LOSDOMAIN_ENV')) {
            return;
        }

        $domain = self::getDomain();

        if (! empty($domain)) {
            putenv("LOSDOMAIN_ENV=$domain");
        } else {
            putenv("LOSDOMAIN_ENV=default");
        }
    }

    /**
     * Returns the layout for the domain. Forwards to the DomainOptions object.
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->getDomainOptions()->getLayout();
    }
}
