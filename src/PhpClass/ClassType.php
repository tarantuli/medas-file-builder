<?php

declare(strict_types=1);

namespace Medas\FileBuilder\PhpClass;

enum ClassType: string
{
    case BasicClass = 'class';
    case Interface = 'interface';
    case Trait = 'trait';
    case Enum = 'enum';
}
