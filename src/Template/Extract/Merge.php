<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

use Atto\Hydrator\Template\ObjectReference;

final class Merge
{
    private const EXTRACT_FORMAT = <<<'EOF'
        foreach ($extract[\%2$s::class](%1$s) as $key => $value) {
            $values['%3$s' . '_' . $key] = $value;
        }
    EOF;

    private readonly ObjectReference $valueReference;

    public function __construct(
        private readonly string $propertyName,
        private readonly string $className,
    ) {
        $this->valueReference = new ObjectReference($this->propertyName);
    }

    public function __toString(): string
    {
        return sprintf(self::EXTRACT_FORMAT, $this->valueReference, $this->className, $this->propertyName);
    }
}