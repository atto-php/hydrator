<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

final class Enum
{
    public function __construct(
        private readonly string|\Stringable $valueReference,
    )
    {
    }

    public function __toString(): string
    {
        return sprintf('%s->value', $this->valueReference);
    }
}