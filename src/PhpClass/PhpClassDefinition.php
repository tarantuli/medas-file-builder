<?php

declare(strict_types=1);

namespace Medas\FileBuilder\PhpClass;

class PhpClassDefinition
{
    public string|null $extends = null;
    public array $implements = [];
    /** @var MethodDefinition[] */
    public array $methods = [];

    public function __construct(
        public string $name,
        public string $namespace,
    )
    {
    }
}
