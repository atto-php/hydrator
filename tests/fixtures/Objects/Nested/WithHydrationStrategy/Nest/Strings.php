<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Objects\Nested\WithHydrationStrategy\Nest;

use Atto\Hydrator\{Attribute\HydrationStrategy, Attribute\HydrationStrategyType, TestFixtures, TestFixtures\Fixture};
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;

final class Strings implements Fixture
{
    public function __construct(
        #[HydrationStrategy(HydrationStrategyType::Nest)]
        private TestFixtures\Scalars\Strings $default,
        #[HydrationStrategy(HydrationStrategyType::Nest)]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private TestFixtures\Scalars\Strings $json,
        #[HydrationStrategy(HydrationStrategyType::Nest)]
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private TestFixtures\Scalars\Strings $commaDelimited,
        #[HydrationStrategy(HydrationStrategyType::Nest)]
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
            'default' => $this->default->getExpectedArray(),
            'json' => $this->json->getExpectedArray(),
            'commaDelimited' => $this->commaDelimited->getExpectedArray(),
            'pipeDelimited' => $this->pipeDelimited->getExpectedArray(),
        ];
    }
}
