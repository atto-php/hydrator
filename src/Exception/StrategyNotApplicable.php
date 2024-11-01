<?php

declare(strict_types=1);

namespace Atto\Hydrator\Exception;

final class StrategyNotApplicable extends \RuntimeException
{
    public static function collectionCannotUseJsonHydration(string $propertyName): StrategyNotApplicable
    {
        return new StrategyNotApplicable(<<<TEXT
            Collection:  "\$$propertyName" cannot support the Json hydration strategy.
            Use the Nest hydration strategy with the Json serialisation strategy instead
            TEXT);
    }

    public static function collectionCannotUseMergeHydration(string $propertyName): StrategyNotApplicable
    {
        return new StrategyNotApplicable(<<<TEXT
            Collection: \$$propertyName" cannot support the Merge hydration strategy.
            TEXT);
    }

    public static function passthroughHydrationCannotSerialise(string $propertyName): StrategyNotApplicable
    {
        return new self(message: sprintf(
            'property "$%s" has the passthrough hydration strategy which leaves data untouched.' .
            ' It cannot use serialisation strategies',
            $propertyName,
        ));
    }
}
