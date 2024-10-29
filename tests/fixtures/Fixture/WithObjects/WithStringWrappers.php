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
        return array_map(
            fn ($subFixture) => new self($subFixture),
            Fixture\WithStringWrappers::getExampleObjects(),
        );
    }

    public function getExpectedObject(): WithStringWrappers
    {
        return new self(
            basic: $this->basic->getExpectedObject(),
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
            ...$mergeKeys('basic', $this->basic->getExpectedArray()),
        ];
    }
}
