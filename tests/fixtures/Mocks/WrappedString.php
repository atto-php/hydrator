<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Mocks;

final class WrappedString implements \Stringable
{
    public function __construct(
        private readonly string $string,
    ) {
    }

    public function __toString(): string
    {
        return $this->string;
    }

}
