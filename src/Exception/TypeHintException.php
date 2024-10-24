<?php

declare(strict_types=1);

namespace Atto\Hydrator\Exception;

final class TypeHintException extends \RuntimeException
{
    public static function missing(string $propertyName): self
    {
        return new self("{$propertyName} requires a typehint");
    }

    public static function unsupported(string $propertyName): self
    {
        return new self(
            "{$propertyName} has an unsupported type" .
            ', only atomic|nullable types are supported'
        );
    }
}
