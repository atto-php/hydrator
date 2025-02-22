<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithEnums;

use Atto\Hydrator\Attribute\Hydratable;
use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;
use Atto\Hydrator\TestFixtures\Mocks\Enums\StringDummy;

#[Hydratable]
final class StringBacked implements Fixture
{
    private StringDummy $unset;
    private ?StringDummy $unsetNullable;
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
        private ?StringDummy $nullable,
        private StringDummy $withDefault = StringDummy::Zero,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        return [
            'Zero' => new self(...array_fill(0, 4, StringDummy::Zero)),
            'One' => new self(...array_fill(0, 4, StringDummy::One)),
            'set nullable properties to null' => new self(
                StringDummy::One,
                StringDummy::One,
                null,
                StringDummy::One,
            ),
            'rely on default' => new self(...array_fill(0, 3, StringDummy::One))
        ];
    }

    public function getExpectedObject(): StringBacked
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
        ];
    }
}
