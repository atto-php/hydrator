<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Scalars\WithDefault;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;

final class Floats implements Fixture
{
    public function __construct(
        private float $default = 3.14,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private float $json = 3.14,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private float $commaDelimited = 3.14,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private float $pipeDelimited = 3.14,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $fillProperties = fn($p) => array_fill(0, 4, $p);

        return [
            'defaults only' => new self(),
            '0.0' => new self(...$fillProperties(0.0)),
            '1.1' => new self(...$fillProperties(1.1)),
            '3.14159' => new self(...$fillProperties(3.14159)),
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
