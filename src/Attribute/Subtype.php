<?php

declare(strict_types=1);

namespace Atto\Hydrator\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Subtype
{
    public function __construct(
        public readonly string $type
    ) {

    }
}