<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Objects\Nested\WithHydrationStrategy\Passthrough;

use Atto\Hydrator\{Attribute\HydrationStrategy, Attribute\HydrationStrategyType, TestFixtures, TestFixtures\Fixture};
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;

final class Bools implements Fixture
{
    public function __construct(
        #[HydrationStrategy(HydrationStrategyType::Passthrough)]
        private TestFixtures\Scalars\Bools $default,
        #[HydrationStrategy(HydrationStrategyType::Passthrough)]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private TestFixtures\Scalars\Bools $json,
        #[HydrationStrategy(HydrationStrategyType::Passthrough)]
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private TestFixtures\Scalars\Bools $commaDelimited,
        #[HydrationStrategy(HydrationStrategyType::Passthrough)]
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
            'default' => $this->default,
            'json' => $this->json,
            'commaDelimited' => $this->commaDelimited,
            'pipeDelimited' => $this->pipeDelimited,
        ];
    }
}
