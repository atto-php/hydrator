<?php

declare(strict_types=1);

namespace Atto\Hydrator\Attribute;

#[\Attribute()]
final class HydrationStrategy
{
    public function __construct(
        public readonly HydrationStrategyType $type
    ) {

    }
}