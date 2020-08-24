<?php

/**
 * Domain file
 *
 * @link       http://github.com/LansoWeb/LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 */

namespace LosDomain;

use function preg_match;

/**
 * Domain class
 *
 * @link       http://github.com/LansoWeb/LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 */
final class Domain
{
    /** @var string|null */
    private $domain;

    public const DEFAULT_DOMAIN = 'default';

    public function __construct(?string $domain = null)
    {
        if ($domain === null) {
            $domain = $this->detectDomain();
        }
        $this->domain = $domain;
    }

    public function domain(): string
    {
        return $this->domain;
    }

    public function toString(): string
    {
        return $this->domain;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    private function detectDomain(): string
    {
        if (isset($_SERVER['HTTP_HOST']) && ! empty($_SERVER['HTTP_HOST'])) {
            if (
                isset($_SERVER['SERVER_PORT'])
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
