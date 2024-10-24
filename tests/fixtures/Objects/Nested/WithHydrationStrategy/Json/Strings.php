<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Objects\Nested\WithHydrationStrategy\Json;

use Atto\Hydrator\{Attribute\HydrationStrategy, Attribute\HydrationStrategyType, TestFixtures, TestFixtures\Fixture};
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;

final class Strings implements Fixture
{
    public function __construct(
        #[HydrationStrategy(HydrationStrategyType::Json)]
        private TestFixtures\Scalars\Strings $default,
        #[HydrationStrategy(HydrationStrategyType::Json)]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private TestFixtures\Scalars\Strings $json,
        #[HydrationStrategy(HydrationStrategyType::Json)]
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private TestFixtures\Scalars\Strings $commaDelimited,
        #[HydrationStrategy(HydrationStrategyType::Json)]
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private TestFixtures\Scalars\Strings $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $fillProperties = fn($p) => array_fill(
            0,
            4,
            new TestFixtures\Scalars\Strings(...array_fill(0, 4, $p)),
        );

        return [
            'empty string' => new self(...$fillProperties('')),
            'Hello' => new self(...$fillProperties('Hello')),
            'Hello, World!' => new self(...$fillProperties('Hello, World!')),
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
