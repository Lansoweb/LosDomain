# LosDomain
[![Build Status](https://travis-ci.org/Lansoweb/LosDomain.svg?branch=master)](https://travis-ci.org/Lansoweb/LosDomain) [![Latest Stable Version](https://poser.pugx.org/los/losdomain/v/stable.svg)](https://packagist.org/packages/los/losdomain) [![Total Downloads](https://poser.pugx.org/los/losdomain/downloads.svg)](https://packagist.org/packages/los/losdomain) [![Coverage Status](https://coveralls.io/repos/Lansoweb/LosDomain/badge.svg?branch=master)](https://coveralls.io/r/Lansoweb/LosDomain?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Lansoweb/LosDomain/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Lansoweb/LosDomain/?branch=master) [![SensioLabs Insight](https://img.shields.io/sensiolabs/i/81b4a9c0-ac7f-4047-9b12-dbe443d13517.svg?style=flat)](https://insight.sensiolabs.com/projects/81b4a9c0-ac7f-4047-9b12-dbe443d13517) [![Dependency Status](https://www.versioneye.com/user/projects/54e8470cd1ec573c99000c04/badge.svg?style=flat)](https://www.versioneye.com/user/projects/54e8470cd1ec573c99000c04)

## Introduction
This module separates layouts and other configuration in files based on the domain (or subdomain) being accessed.

## Requirements
- Zend Framework 2 [framework.zend.com](http://framework.zend.com/).

## Features / Todo

### Features
- Layout per domain
- Possibility to have configuration file per domain (database, view_helpers, etc) 

### Todo
- Option to manually disable a domain, redirecting it to a page. (eg. expired subscription)
- Integration with LosLicense (in development) to check the availibity of a domain and automatically disable it.

## Instalation
Instalation can be done with composer ou manually

### Installation with composer
For composer documentation, please refer to [getcomposer.org](http://getcomposer.org/).

  1. Enter your project directory
  2. Create or edit your `composer.json` file with following contents:

     ```json
     {
         "require": {
             "los/losdomain": "1.*"
         }
     }
     ```
  3. Run `php composer.phar install`
  4. Open `my/project/directory/config/application.config.php` and add `LosDomain` to your `modules`
  5. Copy the file config/losdomain.global.php.dist to <path_to_your_project>/config/autoload/losdomain.global.php 
     and change it's content if necessary.
     
### Installation without composer

  1. Clone this module [LosDomain](http://github.com/LansoWeb/LosDomain) to your vendor directory
  2. Enable it in your config/application.config.php like the step 4 in the previous section and configure as in the step 5.
  
## Usage
First, you need to add a line to your index.php, just after the "require 'init_autoloader.php';":

```php
<?php
chdir(dirname(__DIR__));

if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

require 'init_autoloader.php';

\LosDomain\Service\Domain::setDomainEnv();

Zend\Mvc\Application::init(require 'config/application.config.php')->run();
```

The module will look into the folder 'config/autoload/domains' for your domains (this can be changed in the losdomain.global.php).
If there is a folder named identically as your domain and a 'domain.global.php' (or domain.local.php) file is found, it will be imported.

For example, assume these domains:
- test.local
- client1.test.local
- client2.test.local
- www.test.local

Each domain (or subdomain) can have a different configuration (layout, database, so on):
- config/autoload/domains/test.local/domain.global.php
- config/autoload/domains/client1.test.local/domain.global.php
- config/autoload/domains/www.test.local/domain.global.php

Since the client2.test.local does not have it's configuration, it will use the default from the project.

It's not mandatory, but you can add the domains path to your application.config.php. If not specified, the module will import them during
it's initialization.
```php
<?php
return array(
    'modules' => array(
        'Application',
        'LosDomain'
    ),
    
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor'
        ),
        
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
            'config/autoload/domains/' . getenv('LOSDOMAIN_ENV') . '/domain.{global,local}.php'
        )
    )
);
```

The LOSDOMAIN_ENV variable is set by the setDomainEnv() method in your index.php. You can even use it anywhere in your code to get the domain.

## Domain file
This is an example of a domain file:
```php
<?php
return [
    'client1.test.local' => [
        'layout'  => 'layout/client1.test.local'
    ],
    'client' => [
        'name' => 'Client #1'
    ],
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'params' => [
                    'dbname' = 'test_client1'
                ]
            ]
        ]
    ],
    'view_manager' => [
        'template_map' => [
            'layout/client1.test.local' => __DIR__ . '/view/layout/layout.phtml'
        ]
    ]
];
```

### Domain Alias
It's possible to assign an alias to a domain. Supose you have two domains that share the same configurations and layout:
- test.local
- www.test.local

The first one you create as usual. The second domain needs to be configured as (config/autload/domains/www.test.local/domain.global.php):
```php
return [
    'www.test.local' => [
        'alias' => 'test.local
    ]
];
```

With this configuration, when the project is access through 'www.test.local', the module will load the 'test.local' files.

**Warning!** If you define anything alse, it could be overwritten by it's alias.
