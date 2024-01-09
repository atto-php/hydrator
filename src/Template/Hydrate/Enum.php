<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

final class Enum
{
    public function __construct(
        private readonly string|\Stringable $valueReference,
        private readonly string $className
    )
    {
    }

    public function __toString(): string
    {
        return sprintf('\%2$s::tryFrom(%1$s)', $this->valueReference, $this->className);
    }
}