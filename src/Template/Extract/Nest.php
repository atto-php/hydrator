<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class Nest
{
    use Basic;
    const EXTRACT_FORMAT = '$extract[\%1$s::class](%2$s)';

    private readonly ArrayReference $arrayReference;
    private readonly ObjectReference $objectReference;

    public function __construct(
        string $propertyName,
        private readonly ?SerializationStrategyType $serialisationStrategy,
        private readonly string $className,
        private readonly bool $nullable,
    ) {
        $this->arrayReference = new ArrayReference($propertyName);
        $this->objectReference = new ObjectReference($propertyName);
    }

    private function getExtractFormat(string $objectReference): string
    {
        return sprintf(self::EXTRACT_FORMAT, $this->className, $objectReference);
    }
}
