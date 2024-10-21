<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Arrays;

use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Hydrator\TestFixtures\Fixture;
use Atto\Hydrator\TestFixtures\Scalars\Bools;
use Atto\Hydrator\TestFixtures\Scalars\Floats;
use Atto\Hydrator\TestFixtures\Scalars\Integers;
use Atto\Hydrator\TestFixtures\Scalars\Strings;

final class ObjectsWithScalarProperties implements Fixture
{
    public function __construct(
        #[Subtype(Bools::class)]
        #[HydrationStrategy(HydrationStrategyType::Nest)]
        private array $integers,
        private array $strings,
        private array $bools,
        private array $floats,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        return [
            new self(
                [Bools::getExampleObjects()],
                [Floats::getExampleObjects()],
                [Integers::getExampleObjects()],
                [Strings::getExampleObjects()],
            ),

        ];
    }

    public function getExpectedArray(): array
    {
        return [
            'integers' => json_encode($this->integers),
            'strings' => json_encode($this->strings),
            'bools' => json_encode($this->bools),
            'floats' => json_encode($this->floats),
        ];
    }
}
