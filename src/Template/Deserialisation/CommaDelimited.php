<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Deserialisation;

final class CommaDelimited
{
    public function __construct(
        private readonly string|\Stringable $valueDecoder,
        private readonly string $arrayReference
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            'array_map(fn($value) => %s, explode(\',\', %s))',
            $this->valueDecoder,
            $this->arrayReference
        );
    }
}