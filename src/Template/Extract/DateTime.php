<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

final class DateTime
{
    public function __construct(
        private readonly string|\Stringable $valueReference,
    )
    {
    }

    public function __toString(): string
    {

        return sprintf('%s->format(\DATE_ATOM)', $this->valueReference);
    }
}