<?php

declare(strict_types=1);

namespace Atto\Hydrator\Exception;

final class StrategyNotApplicable extends \RuntimeException
{
    public static function collectionCannotUseJsonHydration(string $propertyName): self
    {
        return new self(<<<TEXT
            Collection:  "\$$propertyName" cannot support the Json hydration strategy.
            Use the Nest hydration strategy with the Json serialisation strategy instead
            TEXT);
    }

    public static function collectionCannotUseMergeHydration(string $propertyName): self
    {
        return new self(<<<TEXT
            Collection: \$$propertyName" cannot support the Merge hydration strategy.
            TEXT);
    }
}
