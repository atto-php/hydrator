<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template;

final class ObjectReference
{
    public function __construct(private readonly string $arrayKey)
    {}

    public function __toString(): string
    {
        return sprintf('$object->%1$s', $this->arrayKey);
    }
}