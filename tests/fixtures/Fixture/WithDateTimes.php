<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture;

use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;
use DateTime;

final class WithDateTimes implements Fixture
{
    private DateTime $unset;
    private ?DateTime $unsetNullable;

    /**
     * @param DateTime $dateTimeHydrationStrategy
     * The hydrator identifies implementations of the DateTimeInterface
     * By default, it acts as if the DateTime hydration strategy was specified
     * So specifying the DateTime hydration strategy should have no effect
     */
    public function __construct(
        private DateTime $basic,
        #[HydrationStrategy(HydrationStrategyType::DateTime)]
        private DateTime $dateTimeHydrationStrategy,
        #[HydrationStrategy(HydrationStrategyType::Passthrough)]
        private DateTime $passthroughHydrationStrategy,
        private ?DateTime $nullable,
        #[HydrationStrategy(HydrationStrategyType::Passthrough)]
        private ?DateTime $nullablePassthroughHydrationStrategy,
        private DateTime $withDefault = new DateTime('1970-01-01'),
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        return [
            '2020-02-02' =>
                new self(...array_fill(0, 6, new DateTime('2020-02-02'))),
            'set nullable properties to null' =>
                new self(
                    new DateTime('2020-02-02'),
                    new DateTime('2020-02-02'),
                    new DateTime('2020-02-02'),
                    null,
                    null,
                    new DateTime('2020-02-02'),
                ),
            'rely on defaults' =>
                new self(...array_fill(0, 5, new DateTime('2020-02-02'))),
        ];
    }

    public function getExpectedObject(): WithDateTimes
    {
        $expected = clone $this;
        $expected->unsetNullable = null;
        return $expected;
    }

    public function getExpectedArray(): array
    {
        return [
            // unset will not appear here
            'unsetNullable' => null,
            'basic' =>
                $this->basic->format(DATE_ATOM),
            'dateTimeHydrationStrategy' =>
                $this->dateTimeHydrationStrategy->format(DATE_ATOM),
            'passthroughHydrationStrategy' =>
                $this->passthroughHydrationStrategy,
            'nullable' =>
                $this->nullable?->format(DATE_ATOM),
            'nullablePassthroughHydrationStrategy' =>
                $this->nullablePassthroughHydrationStrategy,
            'withDefault' =>
                $this->withDefault->format(DATE_ATOM),
        ];
    }
}
