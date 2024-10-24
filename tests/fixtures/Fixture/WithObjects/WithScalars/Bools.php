<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithObjects\WithScalars;

use Atto\Hydrator\{Attribute\HydrationStrategy, Attribute\HydrationStrategyType, TestFixtures\Fixture};
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;

final class Bools implements Fixture
{
    public function __construct(
        private Fixture\WithScalars\Bools $basic,
        #[HydrationStrategy(HydrationStrategyType::Json)]
        private Fixture\WithScalars\Bools $jsonHydrationStrategy,
        #[HydrationStrategy(HydrationStrategyType::Merge)]
        private Fixture\WithScalars\Bools $mergeHydrationStrategy,
        #[HydrationStrategy(HydrationStrategyType::Nest)]
        private Fixture\WithScalars\Bools $nestHydrationStrategy,
        #[HydrationStrategy(HydrationStrategyType::Passthrough)]
        private Fixture\WithScalars\Bools $passthroughHydrationStrategy,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $subFixtures = Fixture\WithScalars\Bools::getExampleObjects();

        $examples = [];
        foreach($subFixtures as $case => $subFixture) {
            assert ($subFixture instanceof Fixture\WithScalars\Bools);
            $examples[$case] = new self(...array_fill(0, 5, $subFixture));
        }

        return $examples;
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

        return [
            ...$mergeKeys(
                'basic',
                $this->basic->getExpectedArray()
            ),
            'jsonHydrationStrategy' =>
                json_encode($this->jsonHydrationStrategy->getExpectedArray()),
            ...$mergeKeys(
                'mergeHydrationStrategy',
                $this->mergeHydrationStrategy->getExpectedArray()
            ),
            'nestHydrationStrategy' =>
                $this->nestHydrationStrategy->getExpectedArray(),
            'passthroughHydrationStrategy' =>
                $this->passthroughHydrationStrategy
        ];
    }
}
