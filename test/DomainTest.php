<?php
namespace LosDomainTest;

use LosDomain\Domain;
use PHPUnit\Framework\TestCase;

class DomainTest extends TestCase
{
    /**
     * @param $httpHost
     * @param $serverName
     * @param $serverPort
     *
     * @dataProvider domainProvider
     */
    public function testDomain($httpHost, $serverName, $serverPort, $expected)
    {
        unset($_SERVER['HTTP_HOST']);
        unset($_SERVER['SERVER_NAME']);
        unset($_SERVER['SERVER_PORT']);
        if (! empty($httpHost)) {
            $_SERVER['HTTP_HOST'] = $httpHost;
        }
        if (! empty($serverName)) {
            $_SERVER['SERVER_NAME'] = $serverName;
        }
        if (! empty($serverPort)) {
            $_SERVER['SERVER_PORT'] = $serverPort;
        }
        $domain = new Domain();
        $this->assertSame($expected, $domain->toString());
        $this->assertSame($expected, $domain->domain());
        $this->assertSame($expected, (string)$domain);
    }

    public function domainProvider()
    {
        return [
            'simple domain' => [ 'abc.com', 'abc.com', '', 'abc.com' ],
            'domain with port' => [ 'abc.com:8080', 'abc.com', 8080, 'abc.com' ],
            'only server_name' => [ '', 'abc.com', 80, 'abc.com' ],
            'no domain' => [ '', '', '', Domain::DEFAULT_DOMAIN ],
        ];
    }
}
