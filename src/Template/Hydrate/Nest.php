<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class Nest
{
    private const HYDRATE_FORMAT = '$hydrate[\%2$s::class](%1$s)';

    private ArrayReference $arrayReference;
    private ObjectReference $objectReference;

    public function __construct(
        private readonly string $propertyName,
        private readonly string $className,
        private readonly bool $nullable,
    ) {
        $this->arrayReference = new ArrayReference($this->propertyName);
        $this->objectReference = new ObjectReference($this->propertyName);
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
            sprintf(self::HYDRATE_FORMAT, $this->arrayReference, $this->className)
        );
    }
}
