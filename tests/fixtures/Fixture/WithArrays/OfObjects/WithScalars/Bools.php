<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithArrays\OfObjects\WithScalars;

use Atto\Hydrator\{Attribute\HydrationStrategy,
    Attribute\HydrationStrategyType,
    Attribute\Subtype,
    TestFixtures\Fixture};
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;

final class Bools implements Fixture
{
    public function __construct(
        #[Subtype(Fixture\WithScalars\Bools::class)]
        #[HydrationStrategy(HydrationStrategyType::Nest)]
        private array $basic,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $subFixtures = Fixture\WithScalars\Bools::getExampleObjects();

        return [
            'empty array' => new self([]),
            'single item' => new self([$subFixtures[0]]),
            'many items' => new self($subFixtures),
        ];
    }

    public function getExpectedObject(): Bools
    {
        return $this;
    }

    public function getExpectedArray(): array
    {
        $mergeKeys = function (string $parentProperty, array $childProperties) {
            $result = [];
            foreach ($childProperties as $childProperty => $value) {
                $result["{$parentProperty}_{$childProperty}"] = $value;
            }

            return $result;
        };

        return [];
    }
}
