<?php

declare(strict_types=1);

namespace Medas\FileBuilder\PhpClass;

readonly class ParameterDefinition implements \Stringable
{
    private const array INTERNAL_TYPES = [
        'bool',
        'int',
        'float',
        'string',
        'array',
        'object',
        'callable',
        'iterable',
        'resource',
        'null',
        'void',
        'never',
        'self',
        'parent',
        'static',
        'mixed',
        'false',
    ];

    private string $type;

    public function __construct(
        string         $type,
        private string $name,
    )
    {
        $this->escapeNonInternalTypes($type);
    }

    private function escapeNonInternalTypes(string $type): void
    {
        $subTypes = explode('|', $type);

        foreach ($subTypes as &$subType) {
            $subType = trim($subType);

            if (!in_array($subType, self::INTERNAL_TYPES)) {
                $subType = '\\' . $subType;
            }
        }

        $this->type = implode('|', $subTypes);
    }

    public function __toString(): string
    {
        return $this->type . ' $' . $this->name;
    }
}
