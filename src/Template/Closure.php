<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template;

final class Closure
{
    private const CODE = <<<'EOF'
        \Closure::bind(static function ($object, &$values) use (&$extract, &$hydrate, &$merge, &$unmerge) {
            %s}, null, \%s::class);
        EOF;


    private array $propertyAccessors;

    public function __construct(
        private readonly string $className
    ) {
    }

    public function addPropertyAccessor(string|\Stringable $propertyAccessor): void
    {
        $this->propertyAccessors[] = $propertyAccessor;
    }

    public function __toString(): string
    {
        return sprintf(
            self::CODE . "\n",
            implode('    ', $this->propertyAccessors),
            $this->className
        );
    }
}