<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithEnums;

use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;
use Atto\Hydrator\TestFixtures\Mocks\Enums\StringDummy;

final class StringBacked implements Fixture
{
    /**
     * @param StringDummy $enumHydrationStrategy
     * The hydrator identifies enums
     * By default, it acts as if the Enum hydration strategy was specified
     * So specifying the Enum hydration strategy should have no effect
     */
    public function __construct(
        private StringDummy $basic,
        #[HydrationStrategy(HydrationStrategyType::Enum)]
        private StringDummy $enumHydrationStrategy,
        private StringDummy $withDefault = StringDummy::Zero,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        return [
            'Zero' => new self(...array_fill(0, 3, StringDummy::Zero)),
            'One' => new self(...array_fill(0, 3, StringDummy::One)),
            'rely on default' => new self(...array_fill(0, 2, StringDummy::One))
        ];
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
