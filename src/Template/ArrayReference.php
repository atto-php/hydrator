<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template;

final class ArrayReference
{
    public function __construct(private readonly string $arrayKey)
    {}

    public function __toString(): string
    {
        return sprintf('$values[\'%1$s\']', $this->arrayKey);
    }
}
