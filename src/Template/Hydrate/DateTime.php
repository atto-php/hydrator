<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class DateTime
{
    use Basic;

    private readonly ArrayReference $arrayReference;
    private readonly ObjectReference $objectReference;
    private readonly string $className;

    public function __construct(
        string $propertyName,
        private readonly ?SerializationStrategyType $serializationStrategy,
        string $className,
        private readonly bool $nullable,
    ) {
        if ($className === \DateTimeInterface::class) {
            $className = \DateTime::class;
        }

        $this->arrayReference = new ArrayReference($propertyName);
        $this->objectReference = new ObjectReference($propertyName);
        $this->className = $className;
    }

    private function getHydrationFormat(string $valueReference): string
    {
        return sprintf('new \%s(%s)', $this->className, $valueReference);
    }
}
