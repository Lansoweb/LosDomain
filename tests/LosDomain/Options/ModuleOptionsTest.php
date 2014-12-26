<?php
namespace LosDomainTest;

use LosDomain\Options\ModuleOptions;

class ModuleOptionsTest extends \PHPUnit_Framework_TestCase
{
    public function testSetInvalidConfigDir()
    {
        $this->setExpectedException('InvalidArgumentException');
        $options = new ModuleOptions(['domain_dir' => 'invalidDir']);
    }

    public function testGetInvalidConfigDir()
    {
        $this->setExpectedException('InvalidArgumentException');
        $options = new ModuleOptions();
        $dir = $options->getDomainDir();
    }
}
