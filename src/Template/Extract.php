<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template;

final class Extract
{
    public function __construct(
        public readonly string $arrayName,
        public readonly string $propertyName,
        public readonly string|\Stringable $valueCreator
    ) {
    }

    public function __toString(): string
    {
        return sprintf('$values[\'%1$s\'] = %2$s;' . "\n", $this->arrayName, $this->valueCreator);
    }
}