<?php

declare(strict_types=1);

namespace Medas\FileBuilder\PhpClass;

class PhpClassDefinition
{
    public ClassType $type = ClassType::BasicClass;
    public bool $isReadonly = false;
    public bool $isAbstract = false;
    public bool $isFinal = false;
    public string|null $extends = null;
    public array $implements = [];

    /** @var MethodDefinition[] */
    public array $methods = [];

    public function __construct(
        public string      $name,
        public string|null $namespace,
    )
    {
    }
}
