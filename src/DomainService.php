<?php

/**
 * Domain Service file
 *
 * @link       http://github.com/LansoWeb/LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 */

namespace LosDomain;

use function array_key_exists;
use function file_exists;
use function glob;

use const DIRECTORY_SEPARATOR;
use const GLOB_BRACE;

/**
 * Domain Service class
 *
 * @link       http://github.com/LansoWeb/LosDomain
 * @license    https://github.com/Lansoweb/LosDomain/blob/master/LICENSE MIT License
 */
final class DomainService
{
    /**
     * Returns um array of php config files per domain
     *
     * @return array Array of files
     */
    public static function configFiles(string $basePath): array
    {
        if (! file_exists($basePath)) {
            return [];
        }
        $domain = new Domain();
        $path   = $domain->toString();
        if (file_exists($e = $basePath . DIRECTORY_SEPARATOR . 'config.php')) {
            $config  = include $e;
            $aliases = $config['los_domain']['aliases'] ?? [];
            if (array_key_exists($domain->toString(), $aliases)) {
                $path = $aliases[$domain->toString()];
            }
        }
        $pattern = $basePath . DIRECTORY_SEPARATOR . $path . '/{{,*.}global,{,*.}local}.php';
        return glob($pattern, GLOB_BRACE);
    }
}
