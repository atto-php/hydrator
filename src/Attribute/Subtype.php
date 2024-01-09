<?php

declare(strict_types=1);

namespace Atto\Hydrator\Attribute;

#[\Attribute()]
final class Subtype
{
    public function __construct(
        public readonly string $type
    ) {

    }
}