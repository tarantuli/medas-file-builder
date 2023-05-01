<?php

declare(strict_types=1);

namespace Medas\FileBuilder;

use Medas\Core\AsSingleton;
use Medas\PhpFormatter\PhpFormatterPackage;
use Medas\ServiceManager\BasePackage;

class FileBuilderPackage extends BasePackage
{
    use AsSingleton;

    public function dependencies(): array
    {
        return $this->dependenciesByClass([
            PhpFormatterPackage::class,
        ]);
    }

    public function sourceDirectory(): string
    {
        return __DIR__;
    }
}
