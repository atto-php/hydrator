<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

final class Nest
{
    public function __construct(
        private readonly string|\Stringable $valueReference,
        private readonly string $className,
        private readonly string $properyName,
    )
    {
    }

    public function __toString(): string
    {
        return sprintf('$unmerge[\%2$s::class](\'%3$s\', %1$s)',  $this->valueReference, $this->className, $this->properyName,);
    }
}