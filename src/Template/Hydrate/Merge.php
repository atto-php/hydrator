<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class Merge
{
    private const UNMERGE = <<<'EOF'
        $hydrateData = [];
        foreach ($properties[\%1$s::class] as $key) {
            $dataKey = '%2$s' . '_' . $key;
            if (array_key_exists($dataKey, $values)) {
                $hydrateData[$key] = $values[$dataKey];
            }
        }
        if (!empty($hydrateData)) {
           %3$s = $hydrate[\%1$s::class]($hydrateData);
        }        
    EOF;

    private ObjectReference $objectReference;

    public function __construct(
        private readonly string|\Stringable $propertyName,
        private readonly string $className
    ) {
        $this->objectReference = new ObjectReference($this->propertyName);
    }

    public function __toString(): string
    {
        return sprintf(
            self::UNMERGE,
            $this->className,
            $this->propertyName,
            $this->objectReference
        );
    }
}