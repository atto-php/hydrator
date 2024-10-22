<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithObjects;

use Atto\Hydrator\TestFixtures\Fixture;

final class WithStringWrappers implements Fixture
{
    public function __construct(
        private Fixture\WithStringWrappers $basic,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $subFixtures = Fixture\WithStringWrappers::getExampleObjects();

        $examples = [];
        foreach($subFixtures as $case => $subFixture) {
            assert ($subFixture instanceof Fixture\WithStringWrappers);
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
