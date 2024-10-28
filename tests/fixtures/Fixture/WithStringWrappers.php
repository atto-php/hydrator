<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture;

use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;
use Atto\Hydrator\TestFixtures\Mocks\WrappedString;

final class WithStringWrappers implements Fixture
{
    public function __construct(
        #[HydrationStrategy(HydrationStrategyType::String)]
        private WrappedString $basic,
        #[HydrationStrategy(HydrationStrategyType::String)]
        private WrappedString $withDefault = new WrappedString('Hello'),
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        return [
            'empty string' =>
                new self(...array_fill(0, 2, new WrappedString(''))),
            'non-empty string' =>
                new self(...array_fill(0, 2, new WrappedString('World!'))),
            'rely on defaults' =>
                new self(...array_fill(0, 1, new WrappedString('World!'))),
        ];
    }

    public function getExpectedObject(): WithStringWrappers
    {
        return $this;
    }

    public function getExpectedArray(): array
    {
        return [
            'basic' =>
                $this->basic->__toString(),
            'withDefault' =>
                $this->withDefault->__toString(),
        ];
    }
}
