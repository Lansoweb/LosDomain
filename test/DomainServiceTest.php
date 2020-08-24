<?php

namespace LosDomainTest;

use LosDomain\DomainService;
use PHPUnit\Framework\TestCase;

class DomainServiceTest extends TestCase
{
    public function testEmptyConfigFiles()
    {
        $this->assertEmpty(DomainService::configFiles(__DIR__ . '/missing_assets'));
    }

    public function testConfigFiles()
    {
        $this->assertCount(2, DomainService::configFiles(__DIR__ . '/assets'));
    }

    public function testConfigFilesFromAlias()
    {
        $_SERVER['HTTP_HOST'] = 'localhost';
        $this->assertCount(2, DomainService::configFiles(__DIR__ . '/assets'));
    }
}
