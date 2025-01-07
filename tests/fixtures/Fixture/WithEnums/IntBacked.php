<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithEnums;

use Atto\Hydrator\Attribute\Hydratable;
use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;
use Atto\Hydrator\TestFixtures\Mocks\Enums\IntDummy;

#[Hydratable]
final class IntBacked implements Fixture
{
    private IntDummy $unset;
    private ?IntDummy $unsetNullable;

    /**
     * @param IntDummy $enumHydrationStrategy
     * The hydrator identifies enums
     * By default, it acts as if the Enum hydration strategy was specified
     * So specifying the Enum hydration strategy should have no effect
     */
    public function __construct(
        private IntDummy $basic,
        #[HydrationStrategy(HydrationStrategyType::Enum)]
        private IntDummy $enumHydrationStrategy,
        private ?IntDummy $nullable,
        private IntDummy $withDefault = IntDummy::Zero,
        private ?IntDummy $nullableWithDefault = IntDummy::Zero,
        private ?IntDummy $nullableWithNullDefault = null,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        return [
            'Zero' => new self(...array_fill(0, 4, IntDummy::Zero)),
            'One' => new self(...array_fill(0, 4, IntDummy::One)),
            'set nullable properties to null' => new self(
                IntDummy::One,
                IntDummy::One,
                null,
                IntDummy::One,
            ),
            'rely on default' => new self(...array_fill(0, 3, IntDummy::One))
        ];
    }

    public function getExpectedObject(): IntBacked
    {
        $expected = clone $this;
        $expected->unsetNullable = null;
        return $expected;
    }

    public function getExpectedArray(): array
    {
        return [
            // unset will not be here
            'unsetNullable' => null,
            'basic' => $this->basic->value,
            'enumHydrationStrategy' => $this->enumHydrationStrategy->value,
            'nullable' => $this->nullable?->value,
            'withDefault' => $this->withDefault->value,
            'nullableWithDefault' => $this->nullableWithDefault?->value,
            'nullableWithNullDefault' => $this->nullableWithNullDefault?->value,
        ];
    }
}
