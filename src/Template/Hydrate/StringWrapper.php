<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class StringWrapper
{
    use Basic;

    private readonly ArrayReference $arrayReference;
    private readonly ObjectReference $objectReference;

    public function __construct(
        string $propertyName,
        private readonly ?SerializationStrategyType $serializationStrategy,
        private readonly string $className,
        private readonly bool $nullable,
    ) {
        $this->arrayReference = new ArrayReference($propertyName);
        $this->objectReference = new ObjectReference($propertyName);
    }

    private function getHydrationFormat(string $valueReference): string
    {
        return sprintf('new \%s(%s)', $this->className, $valueReference);
    }
}
