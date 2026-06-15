<?php

declare(strict_types=1);

use Medas\FileBuilder\FileBuilderPackage;
use Medas\ObjectInstantiator\ObjectInstantiator;
use Medas\ServiceManager\{ServiceConfigBuilder, ServiceManager};

chdir(__DIR__);

new ServiceManager(function (): ServiceConfigBuilder {
    $config = new ServiceConfigBuilder(ObjectInstantiator::class);

    $config->addPackages([
        FileBuilderPackage::instance(),
    ]);

    return $config;
});
