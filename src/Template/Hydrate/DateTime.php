<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

final class DateTime
{
    private readonly string $className;

    public function __construct(
        private readonly string|\Stringable $valueReference,
        string $className
    )
    {
        if ($className === \DateTimeInterface::class) {
            $className = \DateTime::class;
        }

        $this->className = $className;
    }

    public function __toString(): string
    {

        return sprintf('new \%2$s(%1$s)', $this->valueReference, $this->className);
    }
}