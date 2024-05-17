<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class Json
{
    private const CHECKS = <<<'EOF'
        if (
            isset(%1$s) ||
            isset(%2$s) &&
            array_key_exists('%3$s', $values)
        ) {
            %4$s
        }
        EOF;

    private ArrayReference $arrayReference;
    private ObjectReference $objectReference;

    public function __construct(
        private readonly string|\Stringable $propertyName,
        private readonly string $className
    ) {
        $this->arrayReference = new ArrayReference($this->propertyName);
        $this->objectReference = new ObjectReference($this->propertyName);
    }

    public function __toString(): string
    {
        return sprintf(
            self::CHECKS,
            $this->arrayReference,
            $this->objectReference,
            $this->propertyName,
            sprintf(
                '$jsonData = json_decode(%s, true); %s = $hydrate[\%s::class]($jsonData);',
                $this->arrayReference,
                $this->objectReference,
                $this->className
            )
        );
    }
}