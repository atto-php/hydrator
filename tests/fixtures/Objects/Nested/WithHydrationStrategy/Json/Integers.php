<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Objects\Nested\WithHydrationStrategy\Json;

use Atto\Hydrator\{Attribute\HydrationStrategy, Attribute\HydrationStrategyType, TestFixtures, TestFixtures\Fixture};
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;

final class Integers implements Fixture
{
    public function __construct(
        #[HydrationStrategy(HydrationStrategyType::Json)]
        private TestFixtures\Scalars\Integers $default,
        #[HydrationStrategy(HydrationStrategyType::Json)]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private TestFixtures\Scalars\Integers $json,
        #[HydrationStrategy(HydrationStrategyType::Json)]
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private TestFixtures\Scalars\Integers $commaDelimited,
        #[HydrationStrategy(HydrationStrategyType::Json)]
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private TestFixtures\Scalars\Integers $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $fillProperties = fn($p) => array_fill(
            0,
            4,
            new TestFixtures\Scalars\Integers(...array_fill(0, 4, $p)),
        );

        return [
            '0' => new self(...$fillProperties(0)),
            '1' => new self(...$fillProperties(1)),
        ];
    }

    public function getExpectedArray(): array
    {
        return [
            'default' => json_encode($this->default->getExpectedArray()),
            'json' => json_encode($this->json->getExpectedArray()),
            'commaDelimited' => json_encode($this->commaDelimited->getExpectedArray()),
            'pipeDelimited' => json_encode($this->pipeDelimited->getExpectedArray()),
        ];
    }
}
