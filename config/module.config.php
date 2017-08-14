<?php
return [
    'service_manager' => [
        'factories' => [
            \LosDomain\Service\Domain::class => \LosDomain\Service\DomainFactory::class,
            \LosDomain\Options\DomainOptions::class => \LosDomain\Options\DomainOptionsFactory::class,
        ]
    ],
];
