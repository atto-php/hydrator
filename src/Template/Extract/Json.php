<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

use Atto\Hydrator\Template\ObjectReference;

final class Json
{
    const EXTRACT_FORMAT = '$extract[\%2$s::class](%1$s)';
    const ASSIGNMENT = '$values[\'%1$s\'] = %2$s;' . "\n";

    private readonly ObjectReference $valueReference;

    public function __construct(
        private readonly string $propertyName,
        private readonly string $className,
        // private readonly bool $nullable,
    ) {
        $this->valueReference = new ObjectReference($this->propertyName);
    }

    public function __toString(): string
    {
        return sprintf(
            self::ASSIGNMENT,
            $this->propertyName,
            sprintf('json_encode(' . self::EXTRACT_FORMAT . ')', $this->valueReference, $this->className)
        );
    }
}
