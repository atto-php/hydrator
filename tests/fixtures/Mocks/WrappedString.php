<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Mocks;

/**
 * A wrapped string is any class that:
 * - Is Stringable (implements the __toString() method)
 * - Takes a single string argument to construct
 * - Returns an identical string when cast to string.
 */
final class WrappedString implements \Stringable
{
    public function __construct(
        public string $string,
    ) {
    }

    public function __toString(): string
    {
        return $this->string;
    }

}
