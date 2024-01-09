<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

final class Nest
{
    public function __construct(
        private readonly string|\Stringable $valueReference,
        private readonly string $className,
        private readonly string $propertyName,
    )
    {
    }

    public function __toString(): string
    {
        return sprintf('$merge[\%2$s::class](\'%3$s\', $values, %1$s)', $this->valueReference, $this->className, $this->propertyName);
    }
}