<?php

declare(strict_types=1);

namespace Medas\FileBuilder;

use Medas\Core\Attributes\Service;
use Medas\PhpFormatter\{Formatter, Settings\Medas};

#[Service]
readonly class PhpClassBuilder
{
    public function __construct(
        private Formatter $formatter,
    )
    {
    }

    public function build(PhpClass\PhpClassDefinition $classDefinition, bool $doFormat = true): string
    {
        $content = "<?php\n\ndeclare(strict_types=1);\n\n";

        if ($classDefinition->namespace) {
            $content .= "namespace $classDefinition->namespace;\n\n";
        }

        if ($classDefinition->isAbstract) {
            $content .= 'abstract ';
        }

        if ($classDefinition->isFinal) {
            $content .= 'final ';
        }

        if ($classDefinition->isReadonly) {
            $content .= 'readonly ';
        }

        $content .= $classDefinition->type->value . ' ' . $classDefinition->name;

        if ($classDefinition->extends) {
            $content .= ' extends \\' . $classDefinition->extends;
        }

        if ($classDefinition->implements) {
            $content .= ' implements \\' . implode(', \\', $classDefinition->implements);
        }

        $content .= '{' . implode('', $classDefinition->methods);
        $content .= '}';

        if ($doFormat) {
            $content = $this->formatter->format($content, new Medas());
        }

        return $content;
    }
}
