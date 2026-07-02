<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class Merge
{
    private const EXTRACT_FORMAT = <<<'EOF'
        foreach ($extract[\%1$s::class](%2$s) as $key => $value) {
            $values['%3$s' . '_' . $key] = $value;
        }
    EOF;

    private readonly ArrayReference $arrayReference;
    private readonly ObjectReference $objectReference;

    public function __construct(
        private readonly string $propertyName,
        private readonly string $className,
        private readonly bool $nullable,
    ) {
        $this->arrayReference = new ArrayReference($this->propertyName);
        $this->objectReference = new ObjectReference($this->propertyName);
    }

    public function __toString(): string
    {
        if ($this->nullable) {
            $format = <<<PHP
                if (isset($this->objectReference)) {
                    $this->arrayReference = true;
                    %s
                } else {
                    $this->arrayReference = false;
                }
                PHP;
        } else {
            $format = <<<PHP
                if (isset($this->objectReference)) {
                    %s
                }
                PHP;
        }

        return sprintf(
            $format,
            sprintf(
                self::EXTRACT_FORMAT,
                $this->className,
                $this->objectReference,
                $this->propertyName,
            )
        );
    }
}
