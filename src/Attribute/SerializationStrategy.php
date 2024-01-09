<?php

declare(strict_types=1);

namespace Atto\Hydrator\Attribute;

#[\Attribute]
final class SerializationStrategy
{
    public function __construct(public readonly SerializationStrategyType $type)
    {

    }
}