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
        %3$s     
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
        $format = 'if (!empty($hydrateData)) {%1$s = $hydrate[\%2$s::class]($hydrateData);}';
        if ($this->nullable) {
            $format .= 'else {%1$s = null;}';
        }

        return sprintf(
            self::HYDRATE_FORMAT,
            $this->className,
            $this->propertyName,
            sprintf($format, $this->objectReference, $this->className),
        );
    }
}
