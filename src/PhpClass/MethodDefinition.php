<?php

declare(strict_types=1);

namespace Medas\FileBuilder\PhpClass;

class MethodDefinition implements \Stringable
{
    public string $visibility = 'public';
    public bool $isStatic = false;

    /** @var ParameterDefinition[] */
    public array $parameters = [];

    /** @var string[] */
    public array $returnTypes = [];

    public string|null $body = null;

    public function __construct(public string $name)
    {
    }

    public function __toString(): string
    {
        $string = $this->visibility . ' ';

        if (null === $this->body) {
            $string .= 'abstract ';
        }

        if ($this->isStatic) {
            $string .= 'static ';
        }

        $string .= 'function ' . $this->name . '(';

        foreach ($this->parameters as $parameter) {
            $string .= $parameter . ', ';
        }

        if ($this->parameters) {
            $string = substr($string, 0, -2);
        }

        $string .= ')';

        if ($this->returnTypes) {
            $string .= ': ' . implode('|', $this->returnTypes);
        }

        if (null === $this->body) {
            // Abstract method
            $string .= ';';
        }
        else {
            $string .= '{' . $this->body . '}';
        }

        return $string;
    }
}
