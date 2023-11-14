<?php

declare(strict_types=1);

namespace Medas\FileBuilder;

use Medas\Core\Attributes\Service;
use Medas\PhpFormatter\{Formatter, Settings\Medas};

#[Service]
class PhpClassBuilder
{
    private string $content;

    public function __construct(
        private readonly Formatter $formatter,
    )
    {
    }

    public function build(PhpClass\PhpClassDefinition $classDefinition, bool $doFormat = true): string
    {
        $this->content = "<?php\n";

        if ($classDefinition->namespace) {
            $this->content .= "namespace $classDefinition->namespace;\n";
        }

        $this->content .= 'class ' . $classDefinition->name;

        if ($classDefinition->extends) {
            $this->content .= ' extends \\' . $classDefinition->extends;
        }

        if ($classDefinition->implements) {
            $this->content .= ' implements \\' . implode(', \\', $classDefinition->implements);
        }

        $this->content .= '{';

        foreach ($classDefinition->methods as $method) {
            $this->content .= $method;
        }

        $this->content .= '}';

        if ($doFormat) {
            $this->content = $this->formatter->format($this->content, new Medas());
        }

        return $this->content;
    }
}
