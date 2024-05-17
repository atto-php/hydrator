<?php

declare(strict_types=1);

namespace Atto\Hydrator\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class SerializationStrategy
{
    public function __construct(public readonly SerializationStrategyType $type)
    {

    }
}