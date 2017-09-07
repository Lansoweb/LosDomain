# LosDomain
[![Build Status](https://travis-ci.org/Lansoweb/LosDomain.svg?branch=master)](https://travis-ci.org/Lansoweb/LosDomain) [![Latest Stable Version](https://poser.pugx.org/los/losdomain/v/stable.svg)](https://packagist.org/packages/los/losdomain) [![Total Downloads](https://poser.pugx.org/los/losdomain/downloads.svg)](https://packagist.org/packages/los/losdomain) [![Coverage Status](https://coveralls.io/repos/Lansoweb/LosDomain/badge.svg?branch=master)](https://coveralls.io/r/Lansoweb/LosDomain?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Lansoweb/LosDomain/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Lansoweb/LosDomain/?branch=master) [![SensioLabs Insight](https://img.shields.io/sensiolabs/i/81b4a9c0-ac7f-4047-9b12-dbe443d13517.svg?style=flat)](https://insight.sensiolabs.com/projects/81b4a9c0-ac7f-4047-9b12-dbe443d13517) [![Dependency Status](https://www.versioneye.com/user/projects/54e8470cd1ec573c99000c04/badge.svg?style=flat)](https://www.versioneye.com/user/projects/54e8470cd1ec573c99000c04)

## Introduction
This module provides php configuration files based on the domain (or subdomain) being accessed.

For Zend Framework 2/3 [framework.zend.com](http://framework.zend.com/), please use version 1.0.

## Todo

### Todo
- Option to manually disable a domain, redirecting it to a page. (eg. expired subscription)
- Integration with LosLicense (in development) to check the availibity of a domain and automatically disable it.
- Storages to retrieve options for domains (interface, file, pdo, so on)

## Instalation
```bash
composer require los/losdomain:^2.0
```
     
## Usage
For example, in [Zend Expressive](http://zendframework.github.io/zend-expressive/), add the following code to your config/config.php: 

```php
foreach (\LosDomain\DomainService::configFiles('config/autoload/domains') as $file) {
    $config = ArrayUtils::merge($config, include $file);
}
```

Because the included files cannot be cached, it's better to include the code after the cache read/write. So a complete config.php will looks like:
```php
<?php

use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

/**
 * Configuration files are loaded in a specific order. First ``global.php``, then ``*.global.php``.
 * then ``local.php`` and finally ``*.local.php``. This way local settings overwrite global settings.
 *
 * The configuration can be cached. This can be done by setting ``config_cache_enabled`` to ``true``.
 *
 * Obviously, if you use closures in your config you can't cache it.
 */

require __DIR__.'/env.php';

$cachedConfigFile = 'data/cache/app_config.php';

$config = [];
if (is_file($cachedConfigFile)) {
    // Try to load the cached config
    $config = include $cachedConfigFile;
} else {
    // Load configuration from autoload path
    foreach (Glob::glob('config/autoload/{{,*.}global,{,*.}local}.php', Glob::GLOB_BRACE) as $file) {
        $config = ArrayUtils::merge($config, include $file);
    }

    // Cache config if enabled
    if (isset($config['config_cache_enabled']) && $config['config_cache_enabled'] === true) {
        file_put_contents($cachedConfigFile, '<?php return ' . var_export($config, true) . ';');
    }
}

foreach (\LosDomain\DomainService::configFiles('config/autoload/domains') as $file) {
    $config = ArrayUtils::merge($config, include $file);
}

// Return an ArrayObject so we can inject the config as a service in Aura.Di
// and still use array checks like ``is_array``.
return new ArrayObject($config, ArrayObject::ARRAY_AS_PROPS);

```

The service will look into the folder 'config/autoload/domains' for your domains.
If there is a folder named identically as your domain, it will be imported.

For example, assume these domains:
- test.local
- client1.test.local
- client2.test.local
- www.test.local

Each domain (or subdomain) can have a different configuration (factories, database configurations, so on):
- config/autoload/domains/test.local/domain.global.php
- config/autoload/domains/client1.test.local/domain.global.php
- config/autoload/domains/www.test.local/domain.global.php
- config/autoload/domains/www.test.local/domain.local.php

Since the client2.test.local does not have it's configuration, it will use the default from the project.

### Domain Alias
It's possible to assign an alias to a domain. Supose you have two domains that share the same configuration:
- test.local
- www.test.local

The first one you create as usual (creating the config files inside ```config/autoload/domain/test.local/global.php```) 
and the alias is configured in a file ```config/autoload/domains/config.php```
```php
<?php
return [
    'los_domain' => [
        'aliases' => [
            'www.test.local' => 'test.local',
        ],
    ],
];
```

With this configuration, when the project is access through 'www.test.local', the service will load the 'test.local' files.
