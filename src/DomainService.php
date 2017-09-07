<?php
/**
 * Domain Service file
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 * @link       http://github.com/LansoWeb/LosDomain
 */
namespace LosDomain;

/**
 * Domain Service class
 *
 * @author     Leandro Silva <leandro@leandrosilva.info>
 * @category   LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 * @link       http://github.com/LansoWeb/LosDomain
 */
final class DomainService
{

    /**
     * Returns um array of php config files per domain
     *
     * @param string $basePath
     * @return array Array of files
     */
    public static function configFiles(string $basePath) : array
    {
        if (! file_exists($basePath)) {
            return [];
        }
        $domain = new Domain();
        $path = $domain->toString();
        if (file_exists($e = $basePath . DIRECTORY_SEPARATOR . 'config.php')) {
            $config = include $e;
            $aliases = $config['los_domain']['aliases'] ?? [];
            if (array_key_exists($domain->toString(), $aliases)) {
                $path = $aliases[$domain->toString()];
            }
        }
        $pattern = $basePath . DIRECTORY_SEPARATOR . $path . '/{{,*.}global,{,*.}local}.php';
        return glob($pattern, GLOB_BRACE);
    }
}
