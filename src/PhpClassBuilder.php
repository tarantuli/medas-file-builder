<?php

declare(strict_types=1);

namespace Medas\FileBuilder;

use Medas\FileBuilder\PhpClass\PhpClassDefinition;
use Medas\PhpFormatter\Formatter;
use Medas\PhpFormatter\Settings\Medas;
use Medas\ServiceManager\Attributes\Service;

#[Service]
class PhpClassBuilder
{
    private string $content;

    public function __construct(
        private Formatter $formatter,
    )
    {
    }

    public function build(PhpClassDefinition $classDefinition, bool $doFormat = true): string
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
