<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

final class Primitive
{
    public function __construct(
        private readonly string|\Stringable $valueReference,
    )
    {
    }

    public function __toString(): string
    {
        return (string) $this->valueReference;
    }
}