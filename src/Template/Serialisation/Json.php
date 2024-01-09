<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Serialisation;

final class Json
{
    public function __construct(
        private readonly string|\Stringable $valueDecoder,
        private readonly string|\Stringable $propertyReference
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            'json_encode(array_map(fn($value) => %s, %s))',
            $this->propertyReference,
            $this->valueDecoder
        );
    }
}