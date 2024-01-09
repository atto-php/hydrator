<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Deserialisation;

final class Json
{
    public function __construct(
        private readonly string|\Stringable $valueDecoder,
        private readonly string|\Stringable $arrayReference
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            'array_map(fn($value) => %s, json_decode(%s, true))',
            $this->arrayReference,
            $this->valueDecoder
        );
    }
}