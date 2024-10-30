<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class Json
{
    const EXTRACT_FORMAT = 'json_encode($extract[\%1$s::class](%2$s))';

    private readonly ArrayReference $arrayReference;
    private readonly ObjectReference $objectReference;

    public function __construct(
        string $propertyName,
        private readonly string $className,
        private readonly bool $nullable,
    ) {
        $this->arrayReference = new ArrayReference($propertyName);
        $this->objectReference = new ObjectReference($propertyName);
    }

    public function __toString(): string
    {
        $format = 'if (isset(%1$s)) {%2$s = %3$s;}';
        if ($this->nullable) {
            $format .= 'else {%2$s = null;}';
        }

        return sprintf(
            $format,
            $this->objectReference,
            $this->arrayReference,
            sprintf(
                self::EXTRACT_FORMAT,
                $this->className,
                $this->objectReference,
            )
        );
    }
}
