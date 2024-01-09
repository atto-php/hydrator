<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

final class Json
{
    public function __construct(
        private readonly string|\Stringable $valueReference,
        private readonly string $className
    )
    {
    }

    public function __toString(): string
    {
        return sprintf('$hydrate[\%2$s::class](json_decode(%1$s))', $this->valueReference, $this->className);
    }
}