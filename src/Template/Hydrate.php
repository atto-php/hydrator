<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template;

final class Hydrate
{
    private const HYDRATE_CHECKS = <<<'EOF'
        if (
            isset($values['%1$s']) ||
            isset($object->%2$s) &&
            array_key_exists('%1$s', $values)
        ) {
            $object->%2$s = %3$s;
        }
        EOF;

    public function __construct(
        public readonly string $arrayName,
        public readonly string $propertyName,
        public readonly string|\Stringable $valueCreator
    ) {
    }

    public function __toString(): string
    {
        return sprintf(self::HYDRATE_CHECKS . "\n", $this->arrayName, $this->propertyName, $this->valueCreator);
    }
}