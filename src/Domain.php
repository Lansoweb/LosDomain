<?php
/**
 * Domain file
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 * @link       http://github.com/LansoWeb/LosDomain
 */
namespace LosDomain;

/**
 * Domain class
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 * @link       http://github.com/LansoWeb/LosDomain
 */
final class Domain
{
    const DEFAULT_DOMAIN = 'default';

    private $domain;

    /**
     * Domain constructor.
     * @param $domain
     */
    public function __construct(string $domain = null)
    {
        if ($domain === null) {
            $domain = $this->detectDomain();
        }
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function domain() : string
    {
        return $this->domain;
    }

    public function toString() : string
    {
        return $this->domain;
    }

    public function __toString() : string
    {
        return $this->toString();
    }

    private function detectDomain() : string
    {
        if (isset($_SERVER['HTTP_HOST']) && ! empty($_SERVER['HTTP_HOST'])) {
            if (isset($_SERVER['SERVER_PORT'])
                && preg_match('/^(?P<host>.*?):(?P<port>\d+)$/', $_SERVER['HTTP_HOST'], $matches)
            ) {
                return $matches['host'];
            }

            return $_SERVER['HTTP_HOST'];
        }

        if (isset($_SERVER['SERVER_NAME'])) {
            return $_SERVER['SERVER_NAME'];
        }

        return self::DEFAULT_DOMAIN;
    }
}
