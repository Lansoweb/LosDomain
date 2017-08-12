<?php
/**
 * Domain Service file
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 * @link       http://github.com/LansoWeb/LosDomain
 */
namespace LosDomain\Service;

use LosDomain\Options\DomainOptions;

/**
 * Domain Service class
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 * @link       http://github.com/LansoWeb/LosDomain
 */
class Domain
{
    /**
     * The domain options
     * @var \LosDomain\Options\DomainOptions
     */
    protected $options;

    /**
     * Domain constructor.
     * @param DomainOptions $options
     */
    public function __construct(DomainOptions $options)
    {
        $this->options = $options;
    }

    /**
     * Gets the domain. If called statically, it will return the HTTP_HOST from $_SERVER. If not, will return the domain.
     *
     * @return null|string
     */
    public static function getStaticDomain()
    {
        return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->options->getDomain();
    }

    /**
     * @return \LosDomain\Options\DomainOptions
     */
    public function getDomainOptions()
    {
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

        $domain = self::getStaticDomain();

        if (empty($domain)) {
            $domain = 'default';
        }
        putenv("LOSDOMAIN_ENV=$domain");
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
