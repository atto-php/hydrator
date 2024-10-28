<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithEnums;

use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;
use Atto\Hydrator\TestFixtures\Mocks\Enums\IntDummy;

final class IntBacked implements Fixture
{
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
        private IntDummy $withDefault = IntDummy::Zero,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        return [
            'Zero' => new self(...array_fill(0, 3, IntDummy::Zero)),
            'One' => new self(...array_fill(0, 3, IntDummy::One)),
            'rely on default' => new self(...array_fill(0, 2, IntDummy::One))
        ];
    }

    public function getExpectedObject(): IntBacked
    {
        return $this;
    }

    public function getExpectedArray(): array
    {
        return [
            'basic' => $this->basic->value,
            'enumHydrationStrategy' => $this->enumHydrationStrategy->value,
            'withDefault' => $this->withDefault->value,
        ];
    }
}
