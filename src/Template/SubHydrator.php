<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template;

final class SubHydrator
{
    private const HYDRATE = <<<'EOF'
        $hydrate[%s::class] = $%s->create(...);
    EOF;

    private const EXTRACT = <<<'EOF'
        $extract[%s::class] = $%s->extract(...);
    EOF;

    private const PROPERTIES = <<<'EOF'
        $properties[%s::class] = $%2$s::PROPERTIES;       
    EOF;

    public function __construct(
        private readonly string $className,
        private readonly string $parameterName,
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            "%s\n%s\n%s\n",
            sprintf(self::HYDRATE, $this->className, $this->parameterName),
            sprintf(self::EXTRACT, $this->className, $this->parameterName),
            sprintf(self::PROPERTIES, $this->className, $this->parameterName),
        );
    }

}