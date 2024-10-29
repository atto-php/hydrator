<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture;

use Atto\Hydrator\Attribute\Hydratable;
use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;
use Atto\Hydrator\TestFixtures\Mocks\WrappedString;

#[Hydratable]
final class WithStringWrappers implements Fixture
{
    #[HydrationStrategy(HydrationStrategyType::String)]
    private WrappedString $unset;
    #[HydrationStrategy(HydrationStrategyType::String)]
    private ?WrappedString $unsetNullable;

    public function __construct(
        #[HydrationStrategy(HydrationStrategyType::String)]
        private WrappedString $basic,
        #[HydrationStrategy(HydrationStrategyType::String)]
        private ?WrappedString $nullable,
        #[HydrationStrategy(HydrationStrategyType::String)]
        private WrappedString $withDefault = new WrappedString('Hello'),
    ) {
    }

    /** @return WithStringWrappers[] */
    public static function getExampleObjects(): array
    {
        return [
            'empty string' =>
                new self(...array_fill(0, 3, new WrappedString(''))),
            'non-empty string' =>
                new self(...array_fill(0, 3, new WrappedString('World!'))),
            'set nullable to null' =>
                new self(new WrappedString('Howdy'), null, new WrappedString('Planet')),
            'rely on defaults' =>
                new self(...array_fill(0, 2, new WrappedString('World!'))),
        ];
    }

    public function getExpectedObject(): WithStringWrappers
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
            'basic' =>
                $this->basic->__toString(),
            'nullable' =>
                $this->nullable?->__toString(),
            'withDefault' =>
                $this->withDefault->__toString(),
        ];
    }
}
