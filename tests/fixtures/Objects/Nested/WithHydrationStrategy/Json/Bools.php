<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Objects\Nested\WithHydrationStrategy\Json;

use Atto\Hydrator\{Attribute\HydrationStrategy, Attribute\HydrationStrategyType, TestFixtures, TestFixtures\Fixture};
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;

final class Bools implements Fixture
{
    public function __construct(
        #[HydrationStrategy(HydrationStrategyType::Json)]
        private TestFixtures\Scalars\Bools $default,
        #[HydrationStrategy(HydrationStrategyType::Json)]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private TestFixtures\Scalars\Bools $json,
        #[HydrationStrategy(HydrationStrategyType::Json)]
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private TestFixtures\Scalars\Bools $commaDelimited,
        #[HydrationStrategy(HydrationStrategyType::Json)]
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private TestFixtures\Scalars\Bools $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $fillProperties = fn($p) => array_fill(
            0,
            4,
            new TestFixtures\Scalars\Bools(...array_fill(0, 4, $p)),
        );

        return [
            'false' => new self(...$fillProperties(false)),
            'true' => new self(...$fillProperties(true)),
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
