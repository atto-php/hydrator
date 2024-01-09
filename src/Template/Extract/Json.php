<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

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
        return sprintf('json_encode($extract[\%2$s::class](%1$s))', $this->valueReference, $this->className);
    }
}