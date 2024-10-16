<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Scalars;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;

final class Strings implements Fixture
{
    public function __construct(
        private string $default,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private string $json,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private string $commaDelimited,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private string $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $fillProperties = fn($p) => array_fill(0, 4, $p);

        return [
            'empty string' => new self(...$fillProperties('')),
            'Hello' => new self(...$fillProperties('Hello')),
            'Hello, World!' => new self(...$fillProperties('Hello, World!')),
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
