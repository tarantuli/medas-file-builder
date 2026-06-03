# medas-file-builder

Part of the [Medas framework](https://github.com/tarantuli/medas-core).

## Description

Generates PHP source file strings from a structured definition. `PhpClassBuilder` accepts a `PhpClassDefinition` and renders it to a `<?php` file string with `declare(strict_types=1)`, namespace declaration, class/interface/trait/enum header, and method bodies.

The definition model is intentionally simple — it covers the most common code-generation needs without attempting to model every PHP construct:

| Class                 | Purpose                                                                                                           |
|-----------------------|-------------------------------------------------------------------------------------------------------------------|
| `PhpClassDefinition`  | Top-level class/interface/trait/enum with name, namespace, modifiers, extends, implements, and methods            |
| `MethodDefinition`    | A single method with visibility, static flag, parameters, return types, and a body string; `null` body = abstract |
| `ParameterDefinition` | A typed `$name` parameter; non-internal types are automatically prefixed with `\`                                 |
| `ClassType`           | Enum: `BasicClass`, `Interface`, `Trait`, `Enum`                                                                  |

`ParameterDefinition` handles union types — each constituent type is checked against PHP's built-in type list and backslash-escaped if it is a class name.

## Usage

### Package developer context

Register the package and inject `PhpClassBuilder`:

```php
use Medas\FileBuilder\FileBuilderPackage;

FileBuilderPackage::instance();
```

**Generating a basic class:**

```php
use Medas\FileBuilder\PhpClassBuilder;
use Medas\FileBuilder\PhpClass\{ClassType, MethodDefinition, ParameterDefinition, PhpClassDefinition};
use Medas\Core\Attributes\Service;

#[Service]
readonly class EntityScaffolder
{
    public function __construct(
        private PhpClassBuilder $builder,
    ) {}

    public function generate(): string
    {
        $class = new PhpClassDefinition(
            name: 'Invoice',
            namespace: 'MyApp\\Entities',
        );

        $class->type       = ClassType::BasicClass;
        $class->isReadonly = false;
        $class->extends    = 'MyApp\\Entities\\BaseEntity';
        $class->implements = ['MyApp\\Contracts\\HasId'];

        // Constructor
        $constructor = new MethodDefinition('__construct');
        $constructor->parameters[] = new ParameterDefinition('string', 'number');
        $constructor->parameters[] = new ParameterDefinition('int', 'amountCents');
        $constructor->body = '';

        // Method with return type
        $getter = new MethodDefinition('number');
        $getter->returnTypes = ['string'];
        $getter->body = 'return $this->number;';

        $class->methods = [$constructor, $getter];

        return $this->builder->build($class);
    }
}
```

Output:

```php
<?php

declare(strict_types=1);

namespace MyApp\Entities;

abstract class Invoice extends \MyApp\Entities\BaseEntity implements \MyApp\Contracts\HasId{public function __construct(string $number, int $amountCents, ){}public function number(): string{return $this->number;}}
```

**Generating an interface:**

```php
$interface = new PhpClassDefinition('Printable', 'MyApp\\Contracts');
$interface->type = ClassType::Interface;

$method = new MethodDefinition('print');
$method->returnTypes = ['void'];
$method->body = null; // null body = abstract/interface method

$interface->methods = [$method];

$code = $this->builder->build($interface);
```

**Generating a readonly class:**

```php
$class = new PhpClassDefinition('ValueObject', 'MyApp\\Values');
$class->isReadonly = true;
$class->isFinal    = true;

$code = $this->builder->build($class);
```

**Union type parameters:**

```php
// Non-internal types are backslash-escaped automatically
// Internal types (int, string, bool, null, etc.) are left as-is
$param = new ParameterDefinition('MyApp\\Model\\User|null', 'user');
// Renders as: \MyApp\Model\User|null $user

$param = new ParameterDefinition('string|int', 'id');
// Renders as: string|int $id
```

**Abstract methods** — set `body` to `null`:

```php
$method = new MethodDefinition('process');
$method->visibility  = 'protected';
$method->returnTypes = ['void'];
$method->body        = null; // renders as: protected abstract function process(): void;
```

**Static methods:**

```php
$method = new MethodDefinition('create');
$method->isStatic    = true;
$method->returnTypes = ['static'];
$method->body        = 'return new static();';
```

### Backend user context

This package produces raw PHP source strings — writing them to disk is the caller's responsibility. Combine with `medas-entity-generator`'s `FileWriter` and `FileNameFinder` to resolve the correct PSR-4 path and persist the file:

```php
use Medas\EntityGenerator\{FileNameFinder, FileWriter};

$code     = $this->builder->build($classDefinition);
$fileName = $fileNameFinder->find($classDefinition->namespace . '\\' . $classDefinition->name);

$fileWriter->writeToFile($code, $fileName);
```

Note that `PhpClassBuilder` produces a compact single-line representation — whitespace and indentation are not added. If human-readable output is needed, pass the result through a code formatter (e.g. `medas-php-formatter`).
