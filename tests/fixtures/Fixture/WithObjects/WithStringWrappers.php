<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithObjects;

use Atto\Hydrator\Attribute\Hydratable;
use Atto\Hydrator\TestFixtures\Fixture;

#[Hydratable]
final class WithStringWrappers implements Fixture
{
    public function __construct(
        private Fixture\WithStringWrappers $basic,
        private ?Fixture\WithStringWrappers $nullable,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        return array_map(
            fn ($subFixture) => new self(...array_fill(0, 2, $subFixture)),
            Fixture\WithStringWrappers::getExampleObjects(),
        );
    }

    public function getExpectedObject(): WithStringWrappers
    {
        return new self(
            basic: $this->basic->getExpectedObject(),
            nullable: $this->nullable?->getExpectedObject(),
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
            ...isset($this->nullable) ? $mergeKeys('nullable', $this->nullable->getExpectedArray()) : [],
        ];
    }
}
