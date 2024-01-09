<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

final class StringWrapper
{
    public function __construct(
        private readonly string|\Stringable $valueReference
    )
    {
    }

    public function __toString(): string
    {
        return sprintf('(string) %s', $this->valueReference);
    }
}