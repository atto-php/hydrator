<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class Merge
{
    private const HYDRATE_FORMAT = <<<'EOF'
        $hydrateData = [];
        foreach ($properties[\%1$s::class] as $key) {
            $dataKey = '%2$s' . '_' . $key;
            if (array_key_exists($dataKey, $values)) {
                $hydrateData[$key] = $values[$dataKey];
            }
        }  
    EOF;

    private ObjectReference $objectReference;

    public function __construct(
        private readonly string $propertyName,
        private readonly string $className,
        private readonly bool $nullable,
    ) {
        $this->objectReference = new ObjectReference($this->propertyName);
    }

    public function __toString(): string
    {
        if ($this->nullable) {
            $format = 'if (!array_key_exists(\'%1$s\', $values)) {' .
                '%4$s' .
                '%2$s = $hydrate[\%3$s::class]($hydrateData);' .
                '} else {' .
                '%2$s = null;' .
                '}';
        } else {
            $format = '%4$s' . '
            %2$s = $hydrate[\%3$s::class]($hydrateData);';
        }

        return sprintf(
            $format,
            $this->propertyName,
            $this->objectReference,
            $this->className,
            sprintf(self::HYDRATE_FORMAT, $this->className, $this->propertyName),
        );
    }
}
