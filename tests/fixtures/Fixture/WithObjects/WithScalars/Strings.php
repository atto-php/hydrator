<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithObjects\WithScalars;

use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;

final class Strings implements Fixture
{
    public function __construct(
        private Fixture\WithScalars\Strings $basic,
        #[HydrationStrategy(HydrationStrategyType::Json)]
        private Fixture\WithScalars\Strings $jsonHydrationStrategy,
        #[HydrationStrategy(HydrationStrategyType::Json)]
        private ?Fixture\WithScalars\Strings $nullableJsonHydrationStrategy,
        #[HydrationStrategy(HydrationStrategyType::Merge)]
        private Fixture\WithScalars\Strings $mergeHydrationStrategy,
        #[HydrationStrategy(HydrationStrategyType::Merge)]
        private ?Fixture\WithScalars\Strings $nullableMergeHydrationStrategy,
        #[HydrationStrategy(HydrationStrategyType::Nest)]
        private ?Fixture\WithScalars\Strings $nestHydrationStrategy,
        #[HydrationStrategy(HydrationStrategyType::Nest)]
        private ?Fixture\WithScalars\Strings $nullableNestHydrationStrategy,
        #[HydrationStrategy(HydrationStrategyType::Passthrough)]
        private Fixture\WithScalars\Strings $passthroughHydrationStrategy,
        #[HydrationStrategy(HydrationStrategyType::Passthrough)]
        private ?Fixture\WithScalars\Strings $nullablePassthroughHydrationStrategy,
    ) {
    }

    /** @return Strings[] */
    public static function getExampleObjects(): array
    {
        $subFixtures = Fixture\WithScalars\Strings::getExampleObjects();

        $basicSubFixture = current($subFixtures);
        assert ($basicSubFixture instanceof Fixture\WithScalars\Strings);

        $examples = [];
        foreach($subFixtures as $case => $subFixture) {
            $examples[$case] = new self(...array_fill(0, 9, $subFixture));
        }

        $examples['basic subfixtures, but nullable properties set to null'] =
            new self(
                basic: $basicSubFixture,
                jsonHydrationStrategy: $basicSubFixture,
                nullableJsonHydrationStrategy: null,
                mergeHydrationStrategy: $basicSubFixture,
                nullableMergeHydrationStrategy: null,
                nestHydrationStrategy: $basicSubFixture,
                nullableNestHydrationStrategy: null,
                passthroughHydrationStrategy: $basicSubFixture,
                nullablePassthroughHydrationStrategy: null,
            );

        return $examples;
    }

    public function getExpectedObject(): Strings
    {
        return new Strings(
            basic: $this->basic
                ->getExpectedObject(),
            jsonHydrationStrategy: $this->jsonHydrationStrategy
                ->getExpectedObject(),
            nullableJsonHydrationStrategy: $this->nullableJsonHydrationStrategy
                ?->getExpectedObject(),
            mergeHydrationStrategy: $this->mergeHydrationStrategy
                ->getExpectedObject(),
            nullableMergeHydrationStrategy: $this->nullableMergeHydrationStrategy
                ?->getExpectedObject(),
            nestHydrationStrategy: $this->nestHydrationStrategy
                ->getExpectedObject(),
            nullableNestHydrationStrategy: $this->nullableNestHydrationStrategy
                ?->getExpectedObject(),
            passthroughHydrationStrategy: $this->passthroughHydrationStrategy,
            nullablePassthroughHydrationStrategy: $this->nullablePassthroughHydrationStrategy,
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
            ...$mergeKeys('basic', $this->basic
                ->getExpectedArray()),
            'jsonHydrationStrategy' =>
                json_encode($this->jsonHydrationStrategy->getExpectedArray()),
            'nullableJsonHydrationStrategy' => isset($this->nullableJsonHydrationStrategy) ?
                json_encode($this->nullableJsonHydrationStrategy->getExpectedArray()) :
                null,
            ...$mergeKeys('mergeHydrationStrategy', $this->mergeHydrationStrategy
                ->getExpectedArray()),
            ...isset($this->nullableMergeHydrationStrategy) ?
                $mergeKeys('nullableMergeHydrationStrategy', $this->nullableMergeHydrationStrategy->getExpectedArray()) :
                ['nullableMergeHydrationStrategy' => null],
            'nestHydrationStrategy' =>
                $this->nestHydrationStrategy->getExpectedArray(),
            'nullableNestHydrationStrategy' => isset($this->nullableNestHydrationStrategy) ?
                $this->nullableNestHydrationStrategy->getExpectedArray() :
                null,
            'passthroughHydrationStrategy' =>
                $this->passthroughHydrationStrategy,
            'nullablePassthroughHydrationStrategy' =>
                $this->nullablePassthroughHydrationStrategy,
        ];
    }
}
