<?php
namespace LosDomainTest;

use LosDomain\Options\DomainOptions;

class DomainOptionsTest extends \PHPUnit_Framework_TestCase
{

    public function testCanImportDomain()
    {
        $config = DomainOptions::importDomain(__DIR__.'/../_assets/domains', 'test.dev');
        $this->assertArrayHasKey('test.dev',$config);
        $this->assertArrayHasKey('layout',$config['test.dev']);
        $this->assertSame('layout/test.dev',$config['test.dev']['layout']);
        
        $this->assertArrayHasKey('view_manager',$config);
        $this->assertArrayHasKey('template_map',$config['view_manager']);
        $this->assertArrayHasKey('layout/teste.local',$config['view_manager']['template_map']);
        
        $this->assertContains('_assets/domains/test.dev/view/layout/layout_dev.phtml',$config['view_manager']['template_map']['layout/teste.local']);
    }
    
}
