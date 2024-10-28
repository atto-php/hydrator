<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture;

use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;
use DateTime;

final class WithDateTimes implements Fixture
{
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
        private DateTime $withDefault = new DateTime('1970-01-01'),
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        return [
            '2020-02-02' =>
                new self(...array_fill(0, 3, new DateTime('2020-02-02'))),
            'rely on defaults' =>
                new self(...array_fill(0, 2, new DateTime('2020-02-02'))),
        ];
    }

    public function getExpectedObject(): WithDateTimes
    {
        return $this;
    }

    public function getExpectedArray(): array
    {
        return [
            'basic' =>
                $this->basic->format(DATE_ATOM),
            'dateTimeHydrationStrategy' =>
                $this->dateTimeHydrationStrategy->format(DATE_ATOM),
            'withDefault' =>
                $this->withDefault->format(DATE_ATOM),
        ];
    }
}
