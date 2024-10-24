<?php

declare(strict_types=1);

namespace Atto\Hydrator\Exception;

final class AttributeNotApplicable extends \RuntimeException
{
    public function __construct(
        string $attributeName,
        string $propertyType,
        string $propertyName,
    ) {
        parent::__construct(sprintf(
            '%s is not applicable to %s property $%s',
            $attributeName,
            $propertyType,
            $propertyName,
        ));
    }

    public static function subtype(string $propertyType, string $propertyName): self
    {
        return new self('subtype', $propertyType, $propertyName);
    }

    public static function serialisationStrategy(string $propertyType, string $propertyName): self
    {
        return new self('serialisation strategy', $propertyType, $propertyName);
    }
}
