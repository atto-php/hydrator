<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithObjects;

use Atto\Hydrator\{TestFixtures\Fixture};
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;

final class WithDateTimes implements Fixture
{
    public function __construct(
        private Fixture\WithDateTimes $basic,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $subFixtures = Fixture\WithDateTimes::getExampleObjects();

        $examples = [];
        foreach($subFixtures as $case => $subFixture) {
            assert ($subFixture instanceof Fixture\WithDateTimes);
            $examples[$case] = new self($subFixture);
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
            ...$mergeKeys('basic', $this->basic->getExpectedArray()),
        ];
    }
}
