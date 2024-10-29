<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class Passthrough
{
    private ArrayReference $arrayReference;
    private ObjectReference $objectReference;

    public function __construct(
        string $propertyName,
        private readonly bool $nullable,
    ) {
        $this->arrayReference = new ArrayReference($propertyName);
        $this->objectReference = new ObjectReference($propertyName);
    }

    public function __toString(): string
    {
        $format = 'if (isset(%1$s)) {%2$s = %3$s;}';
        if ($this->nullable) {
            $format .= 'else {%2$s = null;}';
        }

        return sprintf(
            $format,
            $this->arrayReference,
            $this->objectReference,
            $this->arrayReference,
        );
    }
}
