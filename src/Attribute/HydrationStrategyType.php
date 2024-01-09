<?php

declare(strict_types=1);

namespace Atto\Hydrator\Attribute;

enum HydrationStrategyType
{
    case Primitive;
    case String;
    case Enum;
    case DateTime;
    case Merge;
    case Nest;
    case Json;

    public function requiresSubHydrator(): bool
    {
        return in_array($this, [self::Merge, self::Nest, self::Json]);
    }
}
