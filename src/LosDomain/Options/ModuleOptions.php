<?php
/**
 * Module Options file
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE BSD-3 License
 * @link       http://github.com/LansoWeb/LosDomain
 */
namespace LosDomain\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Module Options class
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE BSD-3 License
 * @link       http://github.com/LansoWeb/LosDomain
 */
class ModuleOptions extends AbstractOptions
{
    /**
     * Default domain directory
     *
     * @var string
     */
    protected $domainDir = 'config/autoload/domains';

    /**
     * Returns the path to the domains configuration files. Checks if the path exists.
     *
     * @throws \InvalidArgumentException If the specified path does not exists
     * @return string
     */
    public function getDomainDir()
    {
        if (! file_exists($this->domainDir)) {
            throw new \InvalidArgumentException("Directory does not exist!");
        }

        return $this->domainDir;
    }

    /**
     * Sets the path to the domains configuration files. Checks if the path exists.
     *
     * @param string $domainDir
     */
    public function setDomainDir($domainDir)
    {
        $domainDir = trim($domainDir);
        if (! file_exists($domainDir)) {
            throw new \InvalidArgumentException("Directory does not exist!");
        }

        $this->domainDir = $domainDir;
    }
}
