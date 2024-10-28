<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithObjects\WithScalars;

use Atto\Hydrator\{Attribute\HydrationStrategy, Attribute\HydrationStrategyType, TestFixtures\Fixture};
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;

final class Floats implements Fixture
{
    public function __construct(
        private Fixture\WithScalars\Floats $basic,
        #[HydrationStrategy(HydrationStrategyType::Json)]
        private Fixture\WithScalars\Floats $jsonHydrationStrategy,
        #[HydrationStrategy(HydrationStrategyType::Merge)]
        private Fixture\WithScalars\Floats $mergeHydrationStrategy,
        #[HydrationStrategy(HydrationStrategyType::Nest)]
        private Fixture\WithScalars\Floats $nestHydrationStrategy,
        #[HydrationStrategy(HydrationStrategyType::Passthrough)]
        private Fixture\WithScalars\Floats $passthroughHydrationStrategy,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $subFixtures = Fixture\WithScalars\Floats::getExampleObjects();

        $examples = [];
        foreach($subFixtures as $case => $subFixture) {
            assert ($subFixture instanceof Fixture\WithScalars\Floats);
            $examples[$case] = new self(...array_fill(0, 5, $subFixture));
        }

        return $examples;
    }

    public function getExpectedObject(): Floats
    {
        return new Floats(
            basic: $this->basic->getExpectedObject(),
            jsonHydrationStrategy: $this->jsonHydrationStrategy->getExpectedObject(),
            mergeHydrationStrategy: $this->mergeHydrationStrategy->getExpectedObject(),
            nestHydrationStrategy: $this->nestHydrationStrategy->getExpectedObject(),
            passthroughHydrationStrategy: $this->passthroughHydrationStrategy,
        );
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
