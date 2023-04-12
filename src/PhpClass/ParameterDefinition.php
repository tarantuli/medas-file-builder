<?php

declare(strict_types=1);

namespace Medas\FileBuilder\PhpClass;

class ParameterDefinition implements \Stringable
{
    private const INTERNAL_TYPES = [
        'bool', 'int', 'float', 'string', 'array', 'object', 'callable', 'iterable',
        'resource', 'null', 'void', 'never', 'self', 'parent', 'static', 'mixed',
        'false',
    ];

    public function __construct(
        public string $type,
        public string $name,
    )
    {
        if (!in_array($this->type, self::INTERNAL_TYPES)) {
            $this->type = '\\' . $this->type;
        }
    }

    public function __toString(): string
    {
        return $this->type . ' $' . $this->name;
    }
}
